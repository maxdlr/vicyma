<script setup>
import Button from "../../components/atom/VButton.vue";
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
    <a class="my-3 icon-link icon-link-hover" :href="`/admin/user/${correspondant.id}/show`">
      <h3 class="d-lg-inline-block m-0 text-primary">
        {{ correspondant.fullName }}</h3>
      <i class="bi bi-arrow-right-short fs-3"></i>
    </a>

    <div v-if="lodging || reservation" class="list-group rounded-4">
      <div v-if="lodging" class="list-group-item">
        <span>Lodging: </span>
        <a class="fw-bold text-primary icon-link icon-link-hover"
           :href="`/admin/lodging/${lodging.id}/show`">{{ toTitle(lodging.name) }}
          <i class="bi bi-arrow-right-short fs-3"></i>
        </a>
      </div>
      <div v-if="reservation" class="list-group-item">
        <span>Reservation: </span>
        <a class="fw-bold text-primary icon-link icon-link-hover"
           :href="`/admin/reservation/${reservation.id}/show`">{{ reservation.reservationNumber }}
          <i class="bi bi-arrow-right-short fs-3"></i>
        </a>
      </div>
    </div>

  </div>
</template>

<style scoped>

.icon-link > .bi {
  height: unset !important;
}

</style>