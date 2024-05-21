<script setup>
import {computed} from "vue";
import VDatatableRow from "../../components/molecule/VDatatableRow.vue";

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
  <h2>My current reservations</h2>
  <div v-for="(reservation, index) in pendingReservations" :key="index">
    <VDatatableRow :item="reservation" :exclude-properties="['id', 'updatedOn']"/>
  </div>
</template>

<style scoped>

</style>