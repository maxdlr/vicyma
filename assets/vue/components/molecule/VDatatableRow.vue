<script setup>
import {onBeforeMount, ref} from "vue";
import VDatatableCell from "../atom/VDatatableCell.vue";

const props = defineProps({
  item: {type: Object, required: true},
  excludeProperties: {type: Array}
})

const cleanItem = ref({})

onBeforeMount(() => {

  for (const property in props.item) {
    if (!props.excludeProperties.includes(property)) {
      cleanItem.value[property] = props.item[property]
    }
  }
})
</script>

<template>
  <div class="row">
    <div v-for="(property, index) in cleanItem" :key="index" class="col">
      <slot name="cell" :item="{property, index}">
        <VDatatableCell
            v-if="!excludeProperties.includes(index)"
            :name="index"
            :value="property"
        />
      </slot>
    </div>

  </div>
</template>

<style scoped>

</style>