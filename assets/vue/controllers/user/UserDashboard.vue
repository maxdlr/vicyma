<script setup>
import {computed} from "vue";
import VDatatableRow from "../../components/molecule/VDatatableRow.vue";
import Button from "../../components/atom/Button.vue";

const props = defineProps({
  user: {type: Object, required: true},
  reservations: {type: Object, required: true}
})

const pendingReservations = computed(() => {
  let pendingReservations = []

  for (const reservationsKey in props.reservations) {
    if (['PENDING', 'CONFIRMED'].includes(props.reservations[reservationsKey].reservationStatus)) {
      pendingReservations.push(props.reservations[reservationsKey])
    }
  }

  return pendingReservations
})
</script>

<template>
  <div class="d-flex justify-content-between align-items-center">
    <h1>{{ user.firstname }} {{ user.lastname }}</h1>
    <div class="d-flex justify-content-center align-items-center">
      <Button label="Address" icon-class-start="geo-alt-fill" data-bs-toggle="modal"
              data-bs-target="#userAccountAddress"/>
      <Button label="Account settings" icon-class-start="person-fill-gear" data-bs-toggle="modal"
              data-bs-target="#accountSettings"/>
    </div>
  </div>
  <h2>My current reservations</h2>

  <div v-for="(reservation, index) in pendingReservations" :key="index">
    <VDatatableRow :item="reservation" :exclude-properties="['id', 'updatedOn']"/>
  </div>
</template>

<style scoped>

</style>