<script setup>
const query = defineModel('query', {type: String, required: true})
const emit = defineEmits(['typing'])
</script>

<template>
  <div class="form-floating" role="search">
    <input id="searchBar" v-model="query" aria-label="Search" class="form-control" placeholder="Search..."
           type="search" @input="emit('typing')">
    <label class="ps-4 bg-transparent d-flex justify-content-between" for="searchBar">
      <i class="bi bi-search"></i>
    </label>
  </div>
</template>

<style scoped>

</style>