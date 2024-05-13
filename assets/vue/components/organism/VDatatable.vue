<script setup>
import {onBeforeMount, onMounted, ref} from "vue";
import VDatatableResults from "../molecule/VDatatableResults.vue";
import VDatatableSettings from "../molecule/VDatatableSettings.vue";
import VDatatableTitle from "../atom/VDatatableTitle.vue";
import {isEmpty} from "../../composable/formatter/object";
import {clearEmptyLocaleStorage} from "../../composable/action/localStorage";

const props = defineProps({
  data: {type: Object, required: true},
  title: {type: String},
  mainFilter: {type: String, default: null},
  excludeFilters: {type: Array},
  searchableProperties: {type: Array, required: true},
  excludeFromRowProperties: {type: Array},
});
const filteredItems = ref([])
const selectedFilterOptions = ref({});
const selectedOrderByOption = ref({});
const mainFilterValue = ref({name: '', value: ''});
const searchQuery = ref('')
const isLoading = ref(false)

onBeforeMount(() => {
  clearEmptyLocaleStorage()
  setDefaultOrderBy()
})

onMounted(() => {
  isLoading.value = true;
  setDefaultFilters();

  mainFilterValue.value = {
    name: props.mainFilter ? props.data.settings[props.mainFilter].name : null,
    value: props.mainFilter ? selectedFilterOptions.value[props.data.settings[props.mainFilter].name] : null
  }
  isLoading.value = false;
})

const setDefaultOrderBy = () => {
  const storedOrderByKey = `datatable/${props.title}/orderByState`

  if (localStorage.getItem(storedOrderByKey)) {
    selectedOrderByOption.value = JSON.parse(localStorage.getItem(storedOrderByKey))
    localStorage.removeItem(storedOrderByKey);
  } else {
    let defaultOrderBy = '';

    for (const key in props.data.settings) {
      defaultOrderBy = props.data.settings[key];
      break;
    }

    selectedOrderByOption.value = {
      'name': defaultOrderBy.name,
      'codeName': defaultOrderBy.codeName
    }
  }
}

const setDefaultFilters = () => {
  for (const filter in props.data.settings) {
    const storedFilterKey = `datatable/${props.title}/filterState/${props.data.settings[filter].name}`;

    if (localStorage.getItem(storedFilterKey)) {
      selectedFilterOptions.value[props.data.settings[filter].name] = localStorage.getItem(storedFilterKey)
      localStorage.removeItem(storedFilterKey)
    } else {
      selectedFilterOptions.value[props.data.settings[filter].name] = props.data.settings[filter].default;
    }
  }
  filterResults();
}

const resetFilters = () => {
  for (const filter in props.data.settings) {
    selectedFilterOptions.value[props.data.settings[filter].name] = '';
  }
  mainFilterValue.value.value = ''
  searchQuery.value = '';
  filterResults()
}

const orderBy = () => {
  filteredItems.value.sort((a, b) => {
    return ("" + a[selectedOrderByOption.value.codeName]).localeCompare(b[selectedOrderByOption.value.codeName]);
  });
  storeOrderBy()
}

const filterResults = () => {

  if (props.mainFilter) {
    selectedFilterOptions.value[mainFilterValue.value.name] = mainFilterValue.value.value
  }

  let matches = []
  matches = props.data.items.filter((item) => {
    let isMatch = [];
    for (const key in props.data.settings) {
      const selectedFilterValue = selectedFilterOptions.value[props.data.settings[key].name]
      if (selectedFilterValue !== '' && item[key] !== null) {

        if (typeof item[key] === 'boolean') {
          isMatch.push(item[key].toString().length === selectedFilterValue.toString().length)
        }

        if (typeof item[key] === 'object' && !isEmpty(item[key])) {
          isMatch.push(item[key].includes(selectedFilterValue))
        }

        if (typeof item[key] === 'string' || typeof item[key] === 'number') {
          isMatch.push(item[key] === selectedFilterValue)
        }
      }

      if (searchQuery.value !== '' && item[key]) {

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
  storeFilters()
}

const storeFilters = () => {
  for (const setting in props.data.settings) {
    const itemKey = `datatable/${props.title}/filterState/${props.data.settings[setting].name}`;
    const itemValue = selectedFilterOptions.value[props.data.settings[setting].name];
    ["", ' ', '{}'].includes(itemValue) ? localStorage.removeItem(itemKey) : localStorage.setItem(itemKey, itemValue);
  }
}

const storeOrderBy = () => {
  const orderByAsString = selectedOrderByOption.value
  localStorage.setItem(`datatable/${props.title}/orderByState`, JSON.stringify(orderByAsString))
}

</script>

<template>
  <VDatatableTitle v-if="title" :title="title" class="pt-4"/>
  <VDatatableSettings
      :settings="data.settings"
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
  />

  <VDatatableResults
      :items="filteredItems"
      :exclude-from-row-properties="excludeFromRowProperties"
      :is-loading="isLoading"
  >
    <template #buttons="{item}">
      <slot name="buttons" :item="item"/>
    </template>
  </VDatatableResults>
</template>
