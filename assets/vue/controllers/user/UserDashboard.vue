<script setup>
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
              data-bs-target="#userAccountAddress" class="mx-2" color-class="outline-secondary"/>
      <Button label="Account settings" icon-class-start="person-fill-gear" data-bs-toggle="modal"
              data-bs-target="#accountSettings" class="mx-2" color-class="outline-secondary"/>
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
    <template #titleButtons>
      <Button
          label="Send us a message"
          icon-class-start="send-plus-fill"
          data-bs-toggle="modal"
          data-bs-target="#userNewMessage"
      />
    </template>
    <template #buttons="{item}">
      <Button
          label="Open"
          class="my-1"
          color-class="primary"
          @click.prevent="goTo(`${url(item.id)}/show`)"
          icon-class-end="box-arrow-up-right"
      />
      <Button
          label="Ask a question..."
          class="my-1 text-white"
          color-class="info"
          @click.prevent="goTo(`/user/message/about-reservation/${item.id}/ask`)"
          icon-class-end="question-circle-fill"
      />
    </template>
  </VDatatable>
</template>

<style scoped>

</style>