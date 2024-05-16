<script setup>
import Button from "../../components/atom/Button.vue";
import {goTo} from "../../composable/action/redirect";
import {toTitle} from "../../composable/formatter/string";

const props = defineProps({
  correspondant: {type: Object, required: true},
  lodging: {type: Object},
  reservation: {type: Object},
})
</script>

<template>
  <div class="mt-4 px-5">
    <div>
      <h3 class="d-lg-inline-block">Conversation with {{ correspondant.fullName }}</h3>
      <span class="ps-3">
      <Button icon-class-end="box-arrow-up-right" @click.prevent="goTo(`/admin/user/${correspondant.id}/show`)"/>
        </span>
    </div>

    <div v-if="lodging || reservation" class="list-group rounded-4">
      <div v-if="lodging" class="list-group-item list-group-item-action cursor-pointer"
           @click.prevent="goTo(`/admin/lodging/${lodging.id}/show`)">
        <span>Lodging: </span>
        <span class="fw-bold text-primary">{{ toTitle(lodging.name) }}</span>
        <i class="bi bi-box-arrow-up-right ps-4"></i>
      </div>
      <div v-if="reservation" class="list-group-item list-group-item-action cursor-pointer"
           @click.prevent="goTo(`/admin/reservation/${reservation.id}/show`)">
        <span>Reservation: </span>
        <span class="fw-bold text-primary">{{ reservation.reservationNumber }}</span>
        <i class="bi bi-box-arrow-up-right ps-4"></i>
      </div>
    </div>

  </div>
</template>

<style scoped>
.cursor-pointer {
  cursor: pointer;
}

</style>