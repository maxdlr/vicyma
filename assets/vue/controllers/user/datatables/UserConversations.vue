<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";

const props = defineProps({
  data: {type: Object, required: true},
  title: {type: String},
})
</script>

<template>
  <VDatatable
      :searchable-properties="['messages']"
      :data="data"
      :title="title"
  />
</template>

<style scoped>

</style>