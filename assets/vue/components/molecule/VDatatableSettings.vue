<script setup>
import VSearchInput from "../atom/VSearchInput.vue";
import Button from "../atom/Button.vue";
import Dropdown from "../atom/Dropdown.vue";
import {computed} from "vue";
import VDatatableMainFilter from "./VDatatableMainFilter.vue";
import {getDateOptions} from "../../composable/formatter/date";
import {SLIDE_LEFT, SLIDE_RIGHT} from "../../constant/animation";

const props = defineProps({
  settings: {type: Object, required: true},
  excludeFilters: {type: Array, default: [], required: false},
  excludeOrderBys: {type: Array, default: [], required: false},
  mainFilter: {type: String, default: null, required: false},
  dateFilter: {type: Object, default: null, required: false}
})
const searchQuery = defineModel('searchQuery', {type: String, required: true})
const selectedFilterOptions = defineModel('filterOptions', {type: Object, required: true})
const selectedMainFilterOption = defineModel('mainFilterOption', {type: Object})
const selectedOrderByOption = defineModel('orderByOption', {type: Object, required: true})
const selectedDateFilterOption = defineModel('dateFilterOption', {type: [Object, String], required: false})
const activeMainFilter = computed(() => {
  return props.mainFilter ? props.settings[props.mainFilter] : null
})
const activeFilters = computed(() => {
  let activeFilters = {};

  if (props.excludeFilters) {
    for (const key in props.settings) {
      if (!props.excludeFilters.includes(key)) {
        activeFilters[key] = props.settings[key];
      }
    }
  } else {
    activeFilters = {...props.settings};
  }

  if (props.mainFilter) {
    delete activeFilters[props.mainFilter];
  }

  if (props.dateFilter) {
    delete activeFilters[props.dateFilter.codeName]
  }

  return activeFilters;
});
const isFiltered = computed(() => {
  const votes = [];
  for (const filter in props.settings) {
    votes.push(selectedFilterOptions.value[props.settings[filter].codeName] !== '');
  }
  votes.push(searchQuery.value !== '')
  return votes.includes(true)
})

const emit = defineEmits(['search', 'filter', 'reset', 'order'])

const orderByOptions = computed(() => {
  let options = [];
  for (const filterName in props.settings) {
    if (props.settings[filterName].codeName !== props.mainFilter && !props.excludeOrderBys.includes(props.settings[filterName].codeName)) {
      options.push({
        label: props.settings[filterName].name,
        codeName: props.settings[filterName].codeName
      })
    }
  }
  return options
})

const handleMainFilter = (value) => {
  selectedMainFilterOption.value = {
    codeName: activeMainFilter.value.codeName,
    value: value
  }
  emit('filter')
}

const isFilters = computed(() => {
  return Object.keys(activeFilters.value).length !== 0
})

</script>

<template>
  <VDatatableMainFilter
      v-if="mainFilter"
      :filter="activeMainFilter"
      @selected-value="handleMainFilter"
      v-model:active-main-filter="selectedMainFilterOption.value"
      class="py-5"
  />

  <div :class="`row row-cols-${Object.keys(activeFilters).length + 3 + (dateFilter ? 1 : 0)}`"
       class="justify-content-center align-items-center py-4">
    <div class="d-flex justify-content-center align-items-center" v-if="isFilters">
      <h5 class="d-inline my-0 mx-2 p-0 text-center text-secondary">Filters</h5>
      <i class="bi bi-arrow-right-short"></i>
      <Transition :name="SLIDE_RIGHT">
        <div v-if="isFiltered">
          <Button label="Reset" color-class="secondary" @click.prevent="emit('reset')" class="mx-1"/>
        </div>
      </Transition>
    </div>
    <div v-if="isFilters">
      <Dropdown
          v-if="settings.hasOwnProperty(Object.keys(settings)[0])"
          :no-empty="true"
          :options="orderByOptions"
          property-of="label"
          :return-raw-object="true"
          label="Order"
          v-model:selected-option="selectedOrderByOption"
          @has-selection="emit('order')"
      />
    </div>

    <div v-if="dateFilter">
      <Dropdown
          :options="getDateOptions()"
          property-of="name"
          :return-raw-object="true"
          :label="dateFilter.label"
          v-model:selected-option="selectedDateFilterOption"
          @has-selection="emit('filter')"
      />
    </div>

    <div v-for="(filter, index) in activeFilters" :key="index" v-if="isFilters">
      <slot name="filters" :filter="filter">
        <Dropdown
            :label="filter['name']"
            :options="filter['values']"
            property-of="value"
            v-model:selected-option="selectedFilterOptions[filter['codeName']]"
            @has-selection="emit('filter')"
        />
      </slot>
    </div>
    <VSearchInput v-model:query="searchQuery" @typing="emit('search')"/>
  </div>
</template>

<style scoped lang="scss">
@import "../../../styles/animation/slide-left";
@import "../../../styles/animation/slide-right";

</style>