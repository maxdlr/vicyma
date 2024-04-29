<script setup>
import Dropdown from "./Dropdown.vue";
import {onBeforeMount, ref} from "vue";
import VDatatableRow from "../atom/VDatatableRow.vue";
import VDatatableResults from "./VDatatableResults.vue";
import VDatatableSettings from "./VDatatableSettings.vue";

const props = defineProps({
  filters: {type: Object, required: true},
  items: {type: Object, required: true},
  excludeFromRowProperties: {type: Array},
});
const filteredItems = ref([])
const selectedFilterOptions = ref({});
const resultCount = ref(0)

onBeforeMount(() => {
  setDefaultFilters();
})

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
  filterResults()
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
        } else if (typeof item[key] === 'string') {
          isMatch.push(item[key] === selectedFilterValue)
        }
      }
    }
    return !isMatch.includes(false)
  })
  resultCount.value = matches.length;
  filteredItems.value = matches
}

</script>

<template>
  <VDatatableSettings :filters="filters">
    <template #filters="{filter}">
      <Dropdown
          :label="filter['name']"
          :options="filter['values']"
          v-model:selected-option="selectedFilterOptions[filter['name']]"
          @has-selection="filterResults"
      />
    </template>
  </VDatatableSettings>

  <VDatatableResults :items="filteredItems" @reset="resetFilters">
    <template #row="{item}">
      <VDatatableRow :item="item" :exclude-properties="excludeFromRowProperties" class="col"/>
    </template>
    <template #buttons="{item}">
      <slot name="buttons" :item="item"/>
    </template>
  </VDatatableResults>
</template>
