<script setup>
import {computed, onBeforeMount, onMounted, ref} from "vue";
import VDatatableResults from "./VDatatableResults.vue";
import VDatatableSettings from "./VDatatableSettings.vue";
import VDatatableTitle from "../atom/VDatatableTitle.vue";

const props = defineProps({
  title: {type: String, required: true},
  filters: {type: Object, required: true},
  excludeFilters: {type: Array},
  items: {type: Object, required: true},
  searchableProperties: {type: Array, required: true},
  excludeFromRowProperties: {type: Array},
});
const filteredItems = ref([])
const selectedFilterOptions = ref({});
const selectedOrderByOption = ref({});
const resultCount = ref(0)
const searchQuery = ref('')
const isFiltered = computed(() => {
  const vote = [];
  for (const filter in props.filters) {
    vote.push(selectedFilterOptions.value[props.filters[filter].name] !== '');
  }
  vote.push(searchQuery.value !== '')
  return vote.includes(true)
})

onBeforeMount(() => {
  setDefaultOrderBy()
  setDefaultFilters();
})

const setDefaultOrderBy = () => {
  let defaultOrderBy = '';

  for (const key in props.filters) {
    defaultOrderBy = props.filters[key];
    break;
  }

  selectedOrderByOption.value = {
    'name': defaultOrderBy.name,
    'codeName': defaultOrderBy.codeName
  }
}

const setDefaultFilters = () => {
  for (const filter in props.filters) {
    selectedFilterOptions.value[props.filters[filter].name] = props.filters[filter].default;
  }
  filterResults();
}

const resetFilters = () => {
  for (const filter in props.filters) {
    selectedFilterOptions.value[props.filters[filter].name] = '';
  }
  searchQuery.value = '';
  filterResults()
}

const orderBy = () => {
  let options = [];
  for (const filterName in props.filters) {
    options.push(props.filters[filterName].codeName)
  }
  filteredItems.value.sort((a, b) => {
    return ("" + a[selectedOrderByOption.value.codeName]).localeCompare(b[selectedOrderByOption.value.codeName]);
  });
}

const filterResults = () => {
  resultCount.value = 0;
  let matches = []
  matches = props.items.filter((item) => {
    let isMatch = [];
    for (const key in props.filters) {
      const selectedFilterValue = selectedFilterOptions.value[props.filters[key].name]

      if (selectedFilterValue !== '') {

        if (typeof item[key] === 'object') {
          isMatch.push(item[key].includes(selectedFilterValue))
        }

        if (typeof item[key] === 'string') {
          isMatch.push(item[key] === selectedFilterValue)
        }

      }

      if (searchQuery.value !== '') {

        let nestedIsMatch = []
        for (const searchableProperty of props.searchableProperties) {
          if (typeof item[searchableProperty] === 'string') {
            nestedIsMatch.push(item[searchableProperty].toLowerCase().includes(searchQuery.value));
          }

          if (typeof item[searchableProperty] === 'object') {
            for (const searchablePropertyElement of item[searchableProperty]) {
              nestedIsMatch.push(searchablePropertyElement.toLowerCase().includes(searchQuery.value))
            }
          }
        }
        isMatch.push(nestedIsMatch.includes(true))
      }
    }
    return !isMatch.includes(false)
  })
  resultCount.value = matches.length;
  filteredItems.value = matches
  orderBy();
}

</script>

<template>
  <VDatatableTitle :title="title"/>
  <VDatatableSettings
      :filters="filters"
      :is-filtered="isFiltered"
      :exclude-filters="excludeFilters"
      v-model:search-query="searchQuery"
      v-model:order-by-value="selectedOrderByOption"
      v-model:filter-options="selectedFilterOptions"
      @search="filterResults"
      @reset="resetFilters"
      @order="orderBy"
      @filter="filterResults"
  >
  </VDatatableSettings>

  <VDatatableResults :items="filteredItems" :exclude-from-row-properties="excludeFromRowProperties">
    <template #buttons="{item}">
      <slot name="buttons" :item="item"/>
    </template>
  </VDatatableResults>
</template>
