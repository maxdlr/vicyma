<script setup>
import {onBeforeMount, ref} from "vue";
import VDatatableResults from "../molecule/VDatatableResults.vue";
import VDatatableSettings from "../molecule/VDatatableSettings.vue";
import VDatatableTitle from "../atom/VDatatableTitle.vue";

const props = defineProps({
  title: {type: String, required: true},
  settings: {type: Object, required: true},
  mainFilter: {type: String, default: null},
  excludeFilters: {type: Array},
  items: {type: Object, required: true},
  searchableProperties: {type: Array, required: true},
  excludeFromRowProperties: {type: Array},
});
const filteredItems = ref([])
const selectedFilterOptions = ref({});
const selectedOrderByOption = ref({});
const mainFilterValue = ref({
  name: props.mainFilter ? props.settings[props.mainFilter].name : null,
  value: props.mainFilter ? props.settings[props.mainFilter].default : null
});
const searchQuery = ref('')

onBeforeMount(() => {
  setDefaultOrderBy()
  setDefaultFilters();
})

const setDefaultOrderBy = () => {
  let defaultOrderBy = '';

  for (const key in props.settings) {
    defaultOrderBy = props.settings[key];
    break;
  }

  selectedOrderByOption.value = {
    'name': defaultOrderBy.name,
    'codeName': defaultOrderBy.codeName
  }
}

const setDefaultFilters = () => {
  for (const filter in props.settings) {
    selectedFilterOptions.value[props.settings[filter].name] = props.settings[filter].default;
  }
  filterResults();
}

const resetFilters = () => {
  for (const filter in props.settings) {
    selectedFilterOptions.value[props.settings[filter].name] = '';
  }
  searchQuery.value = '';
  filterResults()
}

const orderBy = () => {
  let options = [];
  for (const filterName in props.settings) {
    options.push(props.settings[filterName].codeName)
  }
  filteredItems.value.sort((a, b) => {
    return ("" + a[selectedOrderByOption.value.codeName]).localeCompare(b[selectedOrderByOption.value.codeName]);
  });
}

const filterResults = () => {

  if (props.mainFilter) {
    selectedFilterOptions.value[mainFilterValue.value.name] = mainFilterValue.value.value
  }


  let matches = []
  matches = props.items.filter((item) => {
    let isMatch = [];
    for (const key in props.settings) {
      const selectedFilterValue = selectedFilterOptions.value[props.settings[key].name]

      if (selectedFilterValue !== '') {

        if (typeof item[key] === 'object') {
          isMatch.push(item[key].includes(selectedFilterValue))
        }

        if (typeof item[key] === 'string' || typeof item[key] === 'boolean') {
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
  filteredItems.value = matches
  orderBy();
}

</script>

<template>
  <VDatatableTitle :title="title" class="pt-4"/>
  <VDatatableSettings
      :settings="settings"
      :exclude-filters="excludeFilters"
      :main-filter="mainFilter"
      v-model:search-query="searchQuery"
      v-model:order-by-value="selectedOrderByOption"
      v-model:filter-options="selectedFilterOptions"
      v-model:main-filter-value="mainFilterValue"
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
