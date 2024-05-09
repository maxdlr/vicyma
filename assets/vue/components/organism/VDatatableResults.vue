<script setup>
import Button from "../atom/Button.vue";
import {computed} from "vue";
import VDatatableRow from "../atom/VDatatableRow.vue";

const props = defineProps({
  items: {type: Array, required: true},
  excludeFromRowProperties: {type: Array}
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

  <div v-for="(item) in items" :key="item.id">
    <div
        class="row my-2 justify-content-center align-items-start border border-2 rounded-4 border-primary my-1 px-2 py-3">
      <slot name="row" :item="item">
        <VDatatableRow
            :item="item"
            :exclude-properties="excludeFromRowProperties"
            class="col"
        />
      </slot>
      <div class="d-flex flex-column justify-content-center col-2" v-if="$slots.buttons">
        <slot name="buttons" :item="item"/>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>