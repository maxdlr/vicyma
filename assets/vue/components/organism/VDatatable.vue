<script setup>
import {onBeforeMount, ref, watch} from "vue";
import VDatatableResults from "../molecule/VDatatableResults.vue";
import VDatatableSettings from "../molecule/VDatatableSettings.vue";
import VDatatableTitle from "../atom/VDatatableTitle.vue";
import {isEmpty} from "../../composable/formatter/object";
import {clearEmptyLocaleStorage} from "../../composable/action/localStorage";
import Button from "../atom/VButton.vue";
import {goTo} from "../../composable/action/redirect";

const props = defineProps({
  data: {type: Object, required: true},
  title: {type: String},
  mainFilter: {type: String, default: null},
  excludeFilters: {type: Array},
  excludeOrderBys: {type: Array},
  dateFilter: {type: Object, default: null, required: false},
  searchableProperties: {type: Array, required: true},
  excludeFromRowProperties: {type: Array},
  newItemLink: {type: String, default: null, required: false},
  admin: {type: Boolean, default: false, required: false},
  allowOrderBy: {type: Boolean, default: true},
  hideEmpty: {type: Boolean, default: false}
});
const filteredItems = ref([])
const selectedFilterOptions = ref({});
const selectedOrderByOption = ref({label: '', codeName: ''});
const selectedDateFilterOption = ref({});
const selectedMainFilterOption = ref({codeName: '', value: ''});
const searchQuery = ref('')
const isLoading = ref(false)
const isOrderReversed = ref(false)

onBeforeMount(() => {
  clearEmptyLocaleStorage()
  setDefaultOrderBy()
  setDefaultFilters();

  selectedMainFilterOption.value = {
    codeName: props.mainFilter ? props.data.settings[props.mainFilter].codeName : null,
    value: props.mainFilter ? selectedFilterOptions.value[props.data.settings[props.mainFilter].codeName] : null
  }
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

    selectedOrderByOption.value.label = defaultOrderBy.name;
    selectedOrderByOption.value.codeName = defaultOrderBy.codeName
  }
}

const setDefaultFilters = () => {
  for (const filter in props.data.settings) {
    const storedFilterKey = `datatable/${props.title}/filterState/${props.data.settings[filter].codeName}`;
    if (localStorage.getItem(storedFilterKey)) {
      selectedFilterOptions.value[props.data.settings[filter].codeName] = localStorage.getItem(storedFilterKey)
      localStorage.removeItem(storedFilterKey)
    } else {
      selectedFilterOptions.value[props.data.settings[filter].codeName] = props.data.settings[filter].default;
    }
  }
  selectedDateFilterOption.value = ''
  filterResults();
}

const resetFilters = () => {
  for (const filter in props.data.settings) {
    selectedFilterOptions.value[props.data.settings[filter].codeName] = '';
  }
  selectedMainFilterOption.value.value = ''
  selectedDateFilterOption.value = ''
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

const updateSelectedFilterOptions = () => {
  if (props.mainFilter) {
    selectedFilterOptions.value[selectedMainFilterOption.value.codeName] = selectedMainFilterOption.value.value.toString();
  }

  if (props.dateFilter) {
    selectedFilterOptions.value[props.dateFilter.codeName] = selectedDateFilterOption.value.value ? selectedDateFilterOption.value.value : '';
  }
}

const applyFilters = () => {
  return props.data.items.filter((item) => isItemMatch(item));
}

const isItemMatch = (item) => {
  let votes = [];

  for (const key in props.data.settings) {
    let selectedFilterValue = selectedFilterOptions.value[props.data.settings[key].codeName];

    if (selectedFilterValue !== '' && item[key] !== null) {
      votes.push(checkFilterCondition(item[key], selectedFilterValue));
    }

    if (searchQuery.value !== '' && item[key]) {
      votes.push(checkSearchQuery(item));
    }
  }

  return !votes.includes(false);
}

const checkFilterCondition = (itemValue, selectedFilterValue) => {
  if (typeof itemValue === 'boolean') {
    return itemValue.toString().length === selectedFilterValue.toString().length;
  }
  if (typeof itemValue === 'object' && !isEmpty(itemValue)) {

    if (Array.isArray(itemValue)) {
      let votes = [];
      for (const itemValueElement of itemValue) {
        if (Array.isArray(itemValueElement)) {
          const arrayValueToCheck = itemValueElement[Object.keys(itemValueElement)[1]];
          votes.push(arrayValueToCheck.includes(selectedFilterValue));
        } else {
          votes.push(itemValueElement.includes(selectedFilterValue))
        }
      }
      return votes.includes(true)
    } else {
      const valueToCheck = itemValue[Object.keys(itemValue)[1]]
      return valueToCheck.includes(selectedFilterValue);
    }
  }

  if (typeof selectedFilterValue === 'object') {
    if (new Date(itemValue)) {
      const date = new Date(itemValue)
      return date.getTime() > selectedFilterValue.start.getTime() && date < selectedFilterValue.end.getTime()
    }
  }
  return itemValue.toString() === selectedFilterValue.toString();
}

const checkSearchQuery = (item) => {
  let votes = [];

  for (const searchableProperty of props.searchableProperties) {
    if (typeof item[searchableProperty] === 'string') {
      votes.push(item[searchableProperty].toLowerCase().includes(searchQuery.value.toLowerCase()));
    }

    if (typeof item[searchableProperty] === 'object') {

      if (Array.isArray(item[searchableProperty])) {
        for (const searchablePropertyElement of item[searchableProperty]) {
          if (typeof searchablePropertyElement === 'object') {
            const subEl = searchablePropertyElement[Object.keys(searchablePropertyElement)[1]]
            votes.push(subEl.toLowerCase().includes(searchQuery.value.toLowerCase()));
          } else {
            console.log(searchablePropertyElement)
            votes.push(searchablePropertyElement.toLowerCase().includes(searchQuery.value.toLowerCase()));
          }
        }
      } else {
        for (const searchablePropertyElement in item[searchableProperty]) {
          if (typeof searchablePropertyElement === 'object') {
            const subEl = searchablePropertyElement[Object.keys(searchablePropertyElement)[1]]
            votes.push(subEl.toLowerCase().includes(searchQuery.value.toLowerCase()));
          } else {
            votes.push(item[searchableProperty]['value'].toLowerCase().includes(searchQuery.value.toLowerCase()));
          }
        }
      }
    }
  }

  return votes.includes(true);
}

const filterResults = () => {
  updateSelectedFilterOptions();
  filteredItems.value = applyFilters();
  orderBy();
  storeFilters();
}

const storeFilters = () => {
  for (const setting in props.data.settings) {
    const itemKey = `datatable/${props.title}/filterState/${props.data.settings[setting].codeName}`;
    const itemValue = selectedFilterOptions.value[props.data.settings[setting].codeName];
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
    <div>
      <Button
          label="Create new"
          icon-class-end="plus-circle-fill"
          @click.prevent="goTo(newItemLink)"
          v-if="newItemLink"/>
      <slot name="titleButtons"/>
    </div>
  </div>

  <VDatatableSettings
      :settings="data.settings"
      :exclude-filters="excludeFilters"
      :exclude-order-bys="excludeOrderBys"
      :main-filter="mainFilter"
      :date-filter="dateFilter"
      :allow-order-by="allowOrderBy"
      v-model:search-query="searchQuery"
      v-model:order-by-option="selectedOrderByOption"
      v-model:filter-options="selectedFilterOptions"
      v-model:main-filter-option="selectedMainFilterOption"
      v-model:date-filter-option="selectedDateFilterOption"
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
      :admin="admin"
      :hide-empty="hideEmpty"
  >
    <template #rowHeader="{item}" v-if="$slots.rowHeader">
      <slot name="rowHeader" :item="item"/>
    </template>
    <template #buttons="{item}">
      <slot name="buttons" :item="item"/>
    </template>
  </VDatatableResults>
</template>
