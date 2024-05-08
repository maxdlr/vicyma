<script setup>

import {isEmpty} from "../../composable/formatter/object";

const props = defineProps({
  name: {type: String},
  value: {type: [String, Object, Array]}
})

const templateClass = {
  property: 'py-1 text-center',
}
</script>

<template>
  <div class="position-relative bg-white rounded-3 pt-4">
    <div class="position-absolute top-0 start-0 ps-2 pt-1 opacity-50"><small>{{ name }}</small></div>

    <div v-if="typeof value !== 'object'" :class="templateClass.property">{{ value }}</div>

    <div v-if="typeof value === 'object'" v-for="item in value" :key="value">
      <div :class="templateClass.property">{{ item }}</div>
    </div>
    <div v-if="isEmpty(value) || value.length === 0" :class="templateClass.property">
      <span class="fst-italic opacity-50">--- empty ---</span>
    </div>
  </div>
</template>

<style scoped>

</style>