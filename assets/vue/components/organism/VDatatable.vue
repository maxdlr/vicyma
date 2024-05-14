<script setup>
import {onBeforeMount, onMounted, ref, watch} from "vue";
import VDatatableResults from "../molecule/VDatatableResults.vue";
import VDatatableSettings from "../molecule/VDatatableSettings.vue";
import VDatatableTitle from "../atom/VDatatableTitle.vue";
import {isEmpty} from "../../composable/formatter/object";
import {clearEmptyLocaleStorage} from "../../composable/action/localStorage";
import Button from "../atom/Button.vue";
import {goTo} from "../../composable/action/redirect";

const props = defineProps({
  data: {type: Object, required: true},
  title: {type: String},
  mainFilter: {type: String, default: null},
  excludeFilters: {type: Array},
  dateFilter: {type: String, default: null, required: false},
  searchableProperties: {type: Array, required: true},
  excludeFromRowProperties: {type: Array},
  newItemLink: {type: String, default: null, required: false}
});
const filteredItems = ref([])
const selectedFilterOptions = ref({});
const selectedOrderByOption = ref({});
const selectedDateFilterOption = ref({});
const selectedMainFilterOption = ref({name: '', value: ''});
const searchQuery = ref('')
const isLoading = ref(false)
const isOrderReversed = ref(false)

onBeforeMount(() => {
  clearEmptyLocaleStorage()
  setDefaultOrderBy()
})

onMounted(() => {
  isLoading.value = true;
  setDefaultFilters();

  selectedMainFilterOption.value = {
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
  selectedMainFilterOption.value.value = ''
  searchQuery.value = '';
  filterResults()
}

const orderBy = () => {
  filteredItems.value.sort((a, b) => {
    return ("" + a[selectedOrderByOption.value.codeName]).localeCompare(b[selectedOrderByOption.value.codeName]);
  });
  storeOrderBy()
}

const reverseOrder = () => {
  filteredItems.value.reverse()
}

watch(isOrderReversed, (value) => {
  value ? reverseOrder() : orderBy();
})

// Function to update selected filter options
const updateSelectedFilterOptions = () => {
  if (props.mainFilter) {
    selectedFilterOptions.value[selectedMainFilterOption.value.name] = selectedMainFilterOption.value.value.toString();
  }
  // if (props.dateFilter) {
  //   selectedFilterOptions.value[props.dateFilter] = selectedDateFilterOption.value.value;
  // }
}

// console.log(selectedFilterOptions.value)

// Function to filter items based on selected filter options
const applyFilters = () => {
  return props.data.items.filter((item) => isItemMatch(item));
}

// Function to check if an item matches the filter criteria
const isItemMatch = (item) => {
  let votes = [];

  for (const key in props.data.settings) {
    const selectedFilterValue = selectedFilterOptions.value[props.data.settings[key].name];

    if (selectedFilterValue !== '' && item[key] !== null) {
      votes.push(checkFilterCondition(item[key], selectedFilterValue));
    }

    if (searchQuery.value !== '' && item[key]) {
      votes.push(checkSearchQuery(item));
    }
  }

  return !votes.includes(false);
}

// Function to check filter condition
const checkFilterCondition = (itemValue, selectedFilterValue) => {
  if (typeof itemValue === 'boolean') {
    return itemValue.toString().length === selectedFilterValue.toString().length;
  }

  if (typeof itemValue === 'object' && !isEmpty(itemValue)) {
    return itemValue.includes(selectedFilterValue);
  }

  return itemValue.toString() === selectedFilterValue.toString();
}

// Function to check if item matches search query
const checkSearchQuery = (item) => {
  let votes = [];

  for (const searchableProperty of props.searchableProperties) {
    if (typeof item[searchableProperty] === 'string') {
      votes.push(item[searchableProperty].toLowerCase().includes(searchQuery.value.toLowerCase()));
    }

    if (typeof item[searchableProperty] === 'object') {
      for (const searchablePropertyElement of item[searchableProperty]) {
        votes.push(searchablePropertyElement.toLowerCase().includes(searchQuery.value.toLowerCase()));
      }
    }
  }

  return votes.includes(true);
}

// Main filter function
const filterResults = () => {
  updateSelectedFilterOptions();
  filteredItems.value = applyFilters();
  orderBy();
  storeFilters();
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
  <div class="d-flex justify-content-between align-items-center px-5 pt-4 pb-2">
    <VDatatableTitle v-if="title" :title="title" class="pt-4"/>
    <Button label="Create new" icon-class-end="plus-circle-fill" @click.prevent="goTo(newItemLink)" v-if="newItemLink"/>
  </div>
  <VDatatableSettings
      :settings="data.settings"
      :exclude-filters="excludeFilters"
      :main-filter="mainFilter"
      :date-filter="dateFilter"
      v-model:search-query="searchQuery"
      v-model:order-by-value="selectedOrderByOption"
      v-model:filter-options="selectedFilterOptions"
      v-model:main-filter-value="selectedMainFilterOption"
      @search="filterResults"
      @reset="resetFilters"
      @order="orderBy"
      @filter="filterResults"
  />

  <VDatatableResults
      :items="filteredItems"
      :exclude-from-row-properties="excludeFromRowProperties"
      :is-loading="isLoading"
      v-model:is-order-reversed="isOrderReversed"
  >
    <template #buttons="{item}">
      <slot name="buttons" :item="item"/>
    </template>
  </VDatatableResults>
</template>
