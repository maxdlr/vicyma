<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";

const props = defineProps({
  data: {type: Object, required: true},
  title: {type: String},
})
</script>

<template>
  <VDatatable
      :searchable-properties="['subject', 'content', 'lodging', 'reservation']"
      :data="data"
      :title="title"
  />
</template>

<style scoped>

</style>