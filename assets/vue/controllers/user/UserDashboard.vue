<script setup>
import {computed} from "vue";
import VDatatableRow from "../../components/molecule/VDatatableRow.vue";
import Button from "../../components/atom/VButton.vue";
import VDatatable from "../../components/organism/VDatatable.vue";
import {goTo} from "../../composable/action/redirect";

const props = defineProps({
  user: {type: Object, required: true},
  reservations: {type: Object, required: true}
})

const baseUrl = '/user/reservation';
const url = (id) => `${baseUrl}/${id}`;
</script>

<template>
  <div class="d-flex justify-content-between align-items-center">
    <h1>Bonjour {{ user.firstname }} {{ user.lastname }}</h1>
    <div class="d-flex justify-content-center align-items-center">
      <Button label="Address" icon-class-start="geo-alt-fill" data-bs-toggle="modal"
              data-bs-target="#userAccountAddress"/>
      <Button label="Account settings" icon-class-start="person-fill-gear" data-bs-toggle="modal"
              data-bs-target="#accountSettings"/>
    </div>
  </div>

  <VDatatable
      title="My current reservations"
      :searchable-properties="[
          'reservationNumber'
      ]"
      :data="reservations"
      :exclude-from-row-properties="[
          'id',
          'adultCount',
          'childCount',
          'updatedOn'
      ]"
      :date-filter="{label: 'created on', codeName: 'createdOn'}"
      :allow-order-by="false"
      hide-empty
  >
    <template #buttons="{item}">
      <Button
          label="Open"
          class="my-1"
          color-class="primary"
          @click.prevent="goTo(`${url(item.id)}/show`)"
          icon-class-end="box-arrow-up-right"
      />
    </template>
  </VDatatable>
</template>

<style scoped>

</style>