<script setup>
const props = defineProps({
  user: {type: Object, required: true}
})
</script>

<template>
  {{ user }}
</template>

<style scoped>

</style>