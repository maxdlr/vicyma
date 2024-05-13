<script setup>
import Button from "../atom/Button.vue";
import {computed} from "vue";
import VDatatableRow from "./VDatatableRow.vue";
import LoadingSpinner from "../atom/LoadingSpinner.vue";
import VYoyo from "./VYoyo.vue";

const props = defineProps({
  items: {type: Array, required: true},
  excludeFromRowProperties: {type: Array},
  isLoading: {type: Boolean}
})

const resultCount = computed(() => {
  return props.items.length
});

</script>

<template>
  <div class="p-3">
    <div v-if="resultCount > 0">
      <span>{{ resultCount }} result{{ resultCount > 1 ? 's' : '' }} found!</span>
    </div>
    <div v-if="resultCount === 0">
      <span>No results found!</span>
    </div>
  </div>

  <div v-if="isLoading">
    <LoadingSpinner/>
  </div>

  <div v-for="(item) in items" :key="item.id" v-else>

    <div class="my-2 border border-2 rounded-4 border-primary p-3">
      <slot name="row" :item="item">
        <VDatatableRow
            :item="item"
            :exclude-properties="excludeFromRowProperties"
            class="col"
        >
          <template #buttons>
            <slot name="buttons" :item="item"/>
          </template>
        </VDatatableRow>
      </slot>

    </div>
  </div>
</template>

<style scoped>

</style>