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
    if (props.reservations[reservationsKey].reservationStatus === 'PENDING') {
      pendingReservations.push(props.reservations[reservationsKey])
    }
  }

  return pendingReservations
})
</script>

<template>

  <div v-for="(reservation, index) in pendingReservations" :key="index">
    <VDatatableRow :item="reservation"/>
  </div>
</template>

<style scoped>

</style>