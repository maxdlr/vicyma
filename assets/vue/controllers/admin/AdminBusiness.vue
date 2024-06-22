<script setup>
import AdminReservationRequests from "./datatables/AdminReservationRequests.vue";
import AdminUsers from "./datatables/AdminUsers.vue";
import AdminMessages from "./datatables/AdminMessages.vue";
import AdminReviews from "./datatables/AdminReviews.vue";
import TabbedDatatable from "../../components/molecule/TabbedDatatable.vue";

defineProps({
  datatables: {type: Object, required: true}
})
</script>

<template>
  <TabbedDatatable
      default-tab="reservations"
      :components="{
      reservations: AdminReservationRequests,
      users: AdminUsers,
      messages: AdminMessages,
      reviews: AdminReviews
      }" :datatables="datatables"
  />
</template>