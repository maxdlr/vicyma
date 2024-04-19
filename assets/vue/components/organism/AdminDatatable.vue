<script setup>
import Dropdown from "../molecule/Dropdown.vue";
import {onBeforeMount, ref} from "vue";
import AdminDataRow from "../atom/AdminDataRow.vue";
import Button from "../atom/Button.vue";

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
  <div :class="`row row-cols-${Object.keys(filters).length}`">
    <div v-for="(filter, index) in filters" :key="index">
      <Dropdown
          :label="filter['name']"
          :options="filter['values']"
          v-model:selected-option="selectedFilterOptions[filter['name']]"
          @has-selection="filterResults"
      />
    </div>
  </div>
  <div class="p-3">
    <div v-if="resultCount > 0">{{ resultCount }} result{{ resultCount > 1 ? 's' : '' }} found!</div>
    <div v-if="resultCount === 0">
      <span>No results found!</span>
      <Button label="reset" size="sm" @click.prevent="resetFilters" class="mx-1"/>
    </div>
  </div>

  <div v-for="(item, index) in filteredItems" :key="item.id" class="">
    <div
        class="row my-2 justify-content-center align-items-start border border-2 rounded-4 border-primary my-1 px-2 py-3">
      <AdminDataRow :item="item" :exclude-properties="excludeFromRowProperties" class="col"/>
      <div class="d-flex flex-column justify-content-center col-2">
        <slot name="buttons" :item="item"/>
      </div>
    </div>
  </div>
</template>
