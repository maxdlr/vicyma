<script setup>
import VSearchInput from "../atom/VSearchInput.vue";
import Button from "../atom/Button.vue";
import Dropdown from "./Dropdown.vue";
import {computed} from "vue";

const props = defineProps({
  filters: {type: Object, required: true},
  excludeFilters: {type: Array},
  isFiltered: {type: Boolean, required: true}
})
const searchQuery = defineModel('searchQuery', {type: String, required: true})
const selectedFilterOptions = defineModel('filterOptions', {type: Object, required: true})
const orderByValue = defineModel('orderByValue', {
  type: Object, required: true
})
const activeFilters = computed(() => {
  let activeFilters = {}
  if (props.excludeFilters) {
    for (const key in props.filters) {
      if (!props.excludeFilters.includes(key)) {
        activeFilters += props.filters[key]
      }
    }
  } else {
    activeFilters = props.filters
  }
  return activeFilters
})

const emit = defineEmits(['search', 'filter', 'reset', 'order'])

const orderByOptions = computed(() => {
  let options = [];
  for (const filterName in props.filters) {
    options.push({'name': props.filters[filterName].name, 'codeName': props.filters[filterName].codeName})
  }
  return options
})
</script>

<template>
  <div :class="`row row-cols-${Object.keys(activeFilters).length + 3}`"
       class="justify-content-center align-items-center">
    <div class="d-flex justify-content-center align-items-center">
      <h5 class="d-inline my-0 mx-2 p-0 text-center">Filters</h5>
      <div v-if="isFiltered">
        <Button label="Reset" @click.prevent="emit('reset')" class="mx-1"/>
      </div>
    </div>
    <Dropdown
        :no-empty="true"
        :options="orderByOptions"
        property-of="name"
        label="Order"
        v-model:selected-option="orderByValue"
        @has-selection="emit('order')"
    />
    <div v-for="(filter, index) in activeFilters" :key="index">
      <slot name="filters" :filter="filter">
        <Dropdown
            :label="filter['name']"
            :options="filter['values']"
            v-model:selected-option="selectedFilterOptions[filter['name']]"
            @has-selection="emit('filter')"
        />
      </slot>
    </div>
    <VSearchInput v-model:query="searchQuery" @typing="emit('search')"/>
  </div>
</template>

<style scoped>

</style>