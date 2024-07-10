<script setup>
import InputTextField from "../atom/InputTextField.vue";
// import VueDatePicker from '@vuepic/vue-datepicker';
// import '@vuepic/vue-datepicker/dist/main.css'

</script>

<template>
<div class="ps-0 pt-4 m-0">
<!--  <VueDatePicker/>-->

  <div
      v-for="(_, index) in 3"
       :key="index"
      :class="index === 1 ? 'my-2' : ''"
  >
    <InputTextField />
  </div>
</div>
</template>

<style scoped>

</style>