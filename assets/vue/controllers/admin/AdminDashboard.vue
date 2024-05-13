<script setup>
import VQuickLook from "../../components/organism/VQuickLook.vue";
import {ref} from "vue";
import {implode, toTitle} from "../../composable/formatter/string";
import Button from "../../components/atom/Button.vue";
import {goTo} from "../../composable/action/redirect";

const props = defineProps({
  reservations: {type: Object, required: true}
})

const reservationBaseUrl = '/admin/reservation';

const url = (id) => `${baseUrl}/${id}`;

</script>

<template>

  <article>
    <div class="d-flex justify-content-between align-items-center p-4">
      <div>
        <span class="py-2 px-3 bg-success fs-3 text-white fw-bold rounded-pill">{{ reservations.length }}</span>
        <span class="fs-3 fw-bold"> pending reservations</span>
      </div>
      <div>
        <Button label="See all" @click="goTo('/admin/business#reservations')"/>
      </div>
    </div>
    <div v-for="reservation in reservations" :key="reservation.id">
      <div class="border border-1 border-primary rounded-4 p-4 my-2 d-flex justify-content-between align-items-center">
        <div>

          <div><span class="badge bg-primary">Booked on {{ reservation.createdOn }}</span></div>

          <div>
    <span>
    {{ reservation.user }} wants to book {{ implode(reservation.lodgings) }} from {{
        reservation.arrivalDate
      }} to {{ reservation.departureDate }}
      </span>
          </div>

        </div>
        <div>
          <Button label="Détails..." @click.prevent="goTo(`${reservationBaseUrl}/${reservation.id}/show`)"/>
        </div>
      </div>
    </div>
  </article>

  <!--  <div v-for="(data, index) in quickLooks" :key="index">-->
  <!--    <span class="badge bg-success">{{ data.items.length }}</span>-->
  <!--    <h2 class="d-inline">{{ toTitle(data.name) }}</h2>-->
  <!--    <VQuickLook :items="data.items"/>-->
  <!--  </div>-->
</template>

<style scoped>

</style>