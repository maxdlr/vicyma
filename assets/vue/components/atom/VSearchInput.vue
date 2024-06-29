<script setup>
const query = defineModel('query', {type: String, required: true})
const emit = defineEmits(['typing'])
</script>

<template>
  <div>
    <input
        id="searchBar"
        v-model="query"
        aria-label="Search"
        class="form-control border-info rounded-pill"
        placeholder="Search..."
        type="search"
        @input="emit('typing')"
    >
  </div>
</template>

<style scoped>

</style>