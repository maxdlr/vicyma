<script setup>
import AdminLodgings from "./datatables/AdminLodgings.vue";
import AdminBeds from "./datatables/AdminBeds.vue";
import TabbedDatatable from "../../components/molecule/TabbedDatatable.vue";
import AdminUsers from "./datatables/AdminUsers.vue";

defineProps({
  datatables: {type: Object, required: true}
})

</script>

<template>
  <TabbedDatatable
      default-tab="lodgings"
      :components="{
          lodgings: AdminLodgings,
          beds: AdminBeds,
          users: AdminUsers
      }"
      :datatables="datatables"
      :hidden-empty-datatables="['users', 'beds']"
  />
</template>