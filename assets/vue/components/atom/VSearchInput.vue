<script setup>
const query = defineModel('query', {type: String, required: true})
const emit = defineEmits(['typing'])
</script>

<template>
  <div class="form-floating m-0" role="search">
    <input type="search" v-model="query" @input="emit('typing')" class="form-control" id="searchBar"
           placeholder="Search..." aria-label="Search">
    <label for="searchBar" class="ps-4 bg-transparent d-flex justify-content-between">
      <i class="bi bi-search"></i>
    </label>
  </div>
</template>

<style scoped>

</style>