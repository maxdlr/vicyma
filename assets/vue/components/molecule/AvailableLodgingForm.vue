<script setup>
import InputDate from "../atom/InputDate.vue";
import {ref} from "vue";

const availabilityCheckFormData = ref({
  guestCount: null,
  arrivalDate: null,
  departureDate: null
});

</script>

<template>
  <div class="ps-0 pt-4 m-0">
    <InputDate
        v-model:date="availabilityCheckFormData.arrivalDate"
        label="Dates"
        placeholder="Quelles sont vos dates ?"
        :range="true"
    />
  </div>
</template>

<style scoped>

</style>