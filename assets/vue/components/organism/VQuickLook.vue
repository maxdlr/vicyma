<script setup>
import VDatatableCell from "../atom/VDatatableCell.vue";

const props = defineProps({
  items: {type: Object, required: true}
})

console.log(props.items)
</script>

<template>
  <div class="border border-1 border-primary p-4 rounded-4 row row-cols-3">
    <div v-for="item in items" :key="item.id">
      <div v-for="(value, index) in item" :key="index" class="my-1">
        <div v-if="!['id', 'createdOn'].includes(index)">
          <VDatatableCell :name="index" :value="value"/>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>