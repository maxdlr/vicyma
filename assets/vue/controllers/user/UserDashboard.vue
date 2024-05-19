<script setup>
const props = defineProps({
  user: {type: Object, required: true}
})

console.log(props.user)
</script>

<template>
  {{ user }}
</template>

<style scoped>

</style>