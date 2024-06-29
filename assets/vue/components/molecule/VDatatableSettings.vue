<script setup>
import VSearchInput from "../atom/VSearchInput.vue";
import VButton from "../atom/VButton.vue";
import Dropdown from "../atom/Dropdown.vue";
import {computed, onMounted, onUnmounted, ref} from "vue";
import VDatatableMainFilter from "./VDatatableMainFilter.vue";
import {getDateOptions} from "../../composable/formatter/date";
import {SLIDE_RIGHT} from "../../constant/animation";
import {BREAKPOINTS} from "../../constant/bootstrap-constants";

const props = defineProps({
  settings: {type: Object, required: true},
  excludeFilters: {type: Array, default: []},
  excludeOrderBys: {type: Array, default: []},
  mainFilter: {type: String, default: null},
  dateFilter: {type: Object, default: null},
  hideOrderBy: {type: Boolean, default: false},
  resetButton: {
    type: [String, false], default: 'left', validator(value) {
      return ['left', 'right', false].includes(value)
    }
  },
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

const screenWidth = ref(window.innerWidth);
const screenHeight = ref(window.innerHeight);
const handleResize = () => {
  screenWidth.value = window.innerWidth;
  screenHeight.value = window.innerHeight;
};
onMounted(() => {
  window.addEventListener("resize", handleResize);
});
onUnmounted(() => {
  window.removeEventListener("resize", handleResize);
});

const isMdScreen = computed(() => {
  return screenWidth.value < BREAKPOINTS.LG;
})

</script>

<template>
  <VDatatableMainFilter
      v-if="mainFilter"
      v-model:active-main-filter="selectedMainFilterOption.value"
      :filter="activeMainFilter"
      :screen-height="screenHeight"
      :screen-width="screenWidth"
      class="py-5"
      @selected-value="handleMainFilter"
  />
  <div class="w-100">
    <VSearchInput
        v-if="isMdScreen"
        v-model:query="searchQuery"
        class="w-75 mx-auto"
        @typing="emit('search')"
    />
  </div>
  <div class="d-flex align-items-center">
    <VButton v-if="isFiltered && isMdScreen" class="mx-1" color-class="secondary" label="Reset"
             @click.prevent="emit('reset')"/>
    <div
        id="filters"
        :class="[ isMdScreen ?
      'horizontal-scroll-container' :
      `row row-cols-${Object.keys(activeFilters).length + (!hideOrderBy ? 1 : 0) + (isFiltered ? 1 : 0) + 1 + (dateFilter ? 1 : 0)}`,
      resetButton === 'right' ?
      'justify-content-start' : resetButton === 'left' ?
      'justify-content-end' :
      'justify-content-center',
      ]"
        class="align-items-center py-4"
    >
      <VSearchInput
          v-if="!isMdScreen"
          v-model:query="searchQuery" :class="isMdScreen ? 'horizontal-scroll-item' : ''"
          @typing="emit('search')"
      />

      <div v-if="isFilters && resetButton === 'left' && !isMdScreen"
           :class="isMdScreen ? 'horizontal-scroll-item' : ''"
           class="d-flex justify-content-center align-items-center"
      >
        <h5 class="d-inline my-0 mx-2 p-0 text-center text-secondary">Filters</h5>
        <i class="bi bi-arrow-right-short"></i>
        <Transition :name="SLIDE_RIGHT">
          <VButton v-if="isFiltered" class="mx-1" color-class="secondary" label="Reset" @click.prevent="emit('reset')"/>
        </Transition>
      </div>
      <Dropdown
          v-if="!hideOrderBy && orderByOptions[0]"
          v-model:selected-option="selectedOrderByOption"
          :class="isMdScreen ? 'horizontal-scroll-item' : ''"
          :no-empty="true"
          :options="orderByOptions"
          :return-raw-object="true"
          label="Order"
          property-of="label"
          @has-selection="emit('order')"
      />

      <div v-if="dateFilter"
           :class="isMdScreen ? 'horizontal-scroll-item' : ''"
      >
        <Dropdown
            v-model:selected-option="selectedDateFilterOption"
            :label="dateFilter.label"
            :options="getDateOptions()"
            :return-raw-object="true"
            property-of="name"
            @has-selection="emit('filter')"
        />
      </div>

      <div v-for="(filter, index) in activeFilters" v-if="isFilters" :key="index"
           :class="isMdScreen ? 'horizontal-scroll-item' : ''"
      >
        <Dropdown
            v-model:selected-option="selectedFilterOptions[filter['codeName']]"
            :label="filter['name']"
            :options="filter['values']"
            property-of="value"
            @has-selection="emit('filter')"
        />
      </div>

      <Transition :name="SLIDE_RIGHT">
        <div v-if="isFiltered && resetButton === 'right' && !isMdScreen"
             :class="isMdScreen ? 'horizontal-scroll-item' : ''"
        >
          <VButton class="mx-1" color-class="secondary" label="Reset" @click.prevent="emit('reset')"/>
        </div>
      </Transition>

    </div>
  </div>
</template>

<style lang="scss" scoped>
@import "../../../styles/animation/slide-left";
@import "../../../styles/animation/slide-right";
@import "../../../styles/horizontal-scroll";
</style>