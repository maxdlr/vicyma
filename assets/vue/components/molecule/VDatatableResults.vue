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
  hideEmpty: {type: Boolean, default: false},
  maxCellCountInRow: {type: Number, default: 4, required: false}
})

const resultCount = computed(() => {
  return props.items.length
});

const isOrderReversed = defineModel('isOrderReversed', {type: Boolean, required: true})

</script>

<template>
  <div class="justify-content-between align-items-center d-flex">
    <div class="p-3">
      <div v-if="resultCount > 0">
        <span>{{ resultCount }} result{{ resultCount > 1 ? 's' : '' }} found!</span>
      </div>
      <div v-if="resultCount === 0">
        <span>No results found!</span>
      </div>
    </div>
    <Button
        :icon-class-start="`caret-${isOrderReversed ? 'up' : 'down'}-fill`"
        @click.prevent="isOrderReversed = !isOrderReversed"
    />
  </div>

  <div v-if="isLoading">
    <LoadingSpinner/>
  </div>

  <div v-for="(item) in items" :key="item.id" v-else>

    <slot name="row" :item="item">
      <VDatatableRow
          :item="item"
          :exclude-properties="excludeFromRowProperties"
          :admin="admin"
          :hide-empty="hideEmpty"
          :max-cell-count-in-row="maxCellCountInRow"
      >
        <template #rowHeader="{item}" v-if="$slots.rowHeader">
          <slot name="rowHeader" :item="{item}"/>
        </template>
        <template #buttons>
          <slot name="buttons" :item="item"/>
        </template>
      </VDatatableRow>
    </slot>

  </div>
</template>

<style scoped>

</style>