<script setup>
import {onBeforeMount, ref} from "vue";
import VDatatableCell from "../atom/VDatatableCell.vue";
import VYoyo from "./VYoyo.vue";

const props = defineProps({
  item: {type: Object, required: true},
  excludeProperties: {type: Array}
})

const cleanItem = ref({})
const hovering = ref(false)

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
  <div class="row" @mouseenter="hovering = true" @mouseleave="hovering = false">
    <div v-for="(property, index) in cleanItem" :key="index" class="col">
      <slot name="cell" :item="{property, index}">
        <VDatatableCell
            :name="index"
            :value="property"
        />
      </slot>
    </div>
    <div class="col text-center align-self-center" v-if="$slots.buttons">
      <VYoyo label="Action" direction="down-right" v-model:is-open="hovering">
        <template #buttons>
          <slot name="buttons"/>
        </template>
      </VYoyo>
    </div>
  </div>
</template>

<style scoped>

</style>