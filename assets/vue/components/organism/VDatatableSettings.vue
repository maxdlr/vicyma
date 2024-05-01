<script setup>
import VSearchInput from "../atom/VSearchInput.vue";
import Button from "../atom/Button.vue";

const props = defineProps({
  filters: {type: Object, required: true},
  isFiltered: {type: Boolean, required: true}
})
const searchQuery = defineModel('searchQuery', {type: String, required: true})
const emit = defineEmits(['searching', 'reset'])
</script>

<template>
  <div :class="`row row-cols-${Object.keys(filters).length + 2}`" class="justify-content-center align-items-center">
    <div class="d-flex justify-content-center align-items-center">
      <h5 class="d-inline my-0 mx-2 p-0 text-center">Filters</h5>
      <div v-if="isFiltered">
        <Button label="Reset" @click.prevent="emit('reset')" class="mx-1"/>
      </div>
    </div>
    <div v-for="(filter, index) in filters" :key="index">
      <slot name="filters" :filter="filter"/>
    </div>
    <VSearchInput v-model:query="searchQuery" @typing="emit('searching')"/>
  </div>
</template>

<style scoped>

</style>