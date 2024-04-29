<script setup>
const props = defineProps({
  filters: {type: Object, required: true}
})
</script>

<template>
  <div :class="`row row-cols-${Object.keys(filters).length}`">
    <div v-for="(filter, index) in filters" :key="index">
      <slot name="filters" :filter="filter"/>
    </div>
  </div>
</template>

<style scoped>

</style>