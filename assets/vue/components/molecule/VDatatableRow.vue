<script setup>
import {onBeforeMount, onMounted, ref} from "vue";
import VDatatableCell from "../atom/VDatatableCell.vue";
import VYoyo from "./VYoyo.vue";

const props = defineProps({
  item: {type: Object, required: true},
  excludeProperties: {type: Array}
})

const cleanItem = ref({})
const hovering = ref(false)

const datatableRow = ref(null)
const cellCount = ref(0)

onMounted(() => {
  cellCount.value = datatableRow.value.children.length
})

onBeforeMount(() => {
  for (const property in props.item) {
    if (props.excludeProperties) {
      if (!props.excludeProperties.includes(property)) {
        cleanItem.value[property] = props.item[property]
      }
    } else {
      cleanItem.value[property] = props.item[property]
    }
  }
})
</script>

<template>
  <div v-if="$slots.buttons">
    <VYoyo label="Action" direction="down-left" :is-open="hovering">
      <template #buttons>
        <slot name="buttons"/>
      </template>
    </VYoyo>
  </div>

  <div class="my-2 border border-2 rounded-4 p-3"
       :class="hovering ? 'border-primary' : ''"
       @mouseenter="hovering = true"
       @mouseleave="hovering = false"
  >
    <div class="row"
         :class="`row-cols-${cellCount > 4 ? Math.round(cellCount / 2) : cellCount}`"
         ref="datatableRow"
    >
      <div v-for="(property, index) in cleanItem" :key="index" class="p-2">
        <slot name="cell" :item="{property, index}">
          <VDatatableCell
              :name="index"
              :value="property"
          />
        </slot>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>