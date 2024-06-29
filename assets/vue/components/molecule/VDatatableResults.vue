<script setup>
import Button from "../atom/VButton.vue";
import {computed} from "vue";
import VDatatableRow from "./VDatatableRow.vue";
import LoadingSpinner from "../atom/LoadingSpinner.vue";

const props = defineProps({
  items: {type: Array, required: true},
  excludeFromRowProperties: {type: Array},
  isLoading: {type: Boolean},
  admin: {type: Boolean, default: false, required: false},
  hideEmpty: {type: Boolean},
  maxCellCountInRow: {type: Number, default: 4, required: false},
  hideOrderBy: {type: Boolean, default: false},
  hideResultCount: {type: Boolean, default: false}
})

const resultCount = computed(() => {
  return props.items.length
});

const isOrderReversed = defineModel('isOrderReversed', {type: Boolean, required: true})

</script>

<template>
  <div v-if="!hideResultCount || !hideOrderBy" class="justify-content-between align-items-center d-flex">
    <div v-if="!hideResultCount" class="p-3">
      <div v-if="resultCount > 0">
        <span>{{ resultCount }} result{{ resultCount > 1 ? 's' : '' }} found!</span>
      </div>
      <div v-if="resultCount === 0">
        <span>No results found!</span>
      </div>
    </div>
    <Button
        v-if="!hideOrderBy"
        :icon-class-start="`caret-${isOrderReversed ? 'up' : 'down'}-fill`"
        @click.prevent="isOrderReversed = !isOrderReversed"
    />
  </div>

  <div v-if="isLoading">
    <LoadingSpinner/>
  </div>

  <div v-for="(item) in items" v-else :key="item.id">

    <slot :item="item" name="row">
      <VDatatableRow
          :admin="admin"
          :exclude-properties="excludeFromRowProperties"
          :hide-empty="hideEmpty"
          :item="item"
          :max-cell-count-in-row="maxCellCountInRow"
      >
        <template v-if="$slots.rowHeader" #rowHeader="{item}">
          <slot :item="{item}" name="rowHeader"/>
        </template>
        <template #buttons>
          <slot :item="item" name="buttons"/>
        </template>
      </VDatatableRow>
    </slot>

  </div>
</template>

<style scoped>

</style>