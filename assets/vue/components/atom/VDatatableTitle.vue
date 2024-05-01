<script setup>
const props = defineProps({
  title: {type: String, required: true},
  description: {type: String}
})
</script>

<template>
  <div>
    <h1>{{ title }}</h1>
    <p v-if="description">{{ description }}</p>
  </div>
</template>

<style scoped>

</style>