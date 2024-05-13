<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";
import Button from "../../../components/atom/Button.vue";
import {getPropertyValue} from "../../../composable/formatter/object";
import {goTo} from "../../../composable/action/redirect";

defineProps({
  data: {type: Object, required: true},
  title: {type: String},
});

const baseUrl = '/admin/reservation';

const url = (id) => `${baseUrl}/${id}`;
const canBeConfirmed = (object) => ['PENDING'].includes(getPropertyValue(object, 'reservationStatus'))
const canBeArchived = (object) => ['PENDING', 'CONFIRMED', 'PAID'].includes(getPropertyValue(object, 'reservationStatus'))
const canBeDeleted = (object) => ['PENDING', 'ARCHIVED'].includes(getPropertyValue(object, 'reservationStatus'))
const canBePaid = (object) => ['PENDING', 'CONFIRMED'].includes(getPropertyValue(object, 'reservationStatus'))

</script>

<template>
  <VDatatable
      :title="title"
      :data="data"
      main-filter="reservationStatus"
      :exclude-from-row-properties="['id', 'reservationStatus']"
      :searchable-properties="['user', 'lodgings', 'reservationNumber']"
  >
    <template #buttons="{item}">
      <Button
          label="Détails..."
          color-class="secondary"
          class="my-1"
          icon-class-end="box-arrow-up-right"
          @click.prevent="goTo(`${url(item.id)}/show`)"/>
      <Button v-if="canBeConfirmed(item)"
              label="Confirm"
              class="my-1"
              color-class="success"
              icon-class-end="check-all"
              @click.prevent="goTo(
                        `${url(item.id)}/confirm`,
                        `Salut Maman, tu veux vraiment confirmer la reservation de ${item.user} ?`
                        )"
      />
      <Button v-if="canBePaid(item)"
              label="Set as paid"
              class="my-1"
              color-class="success"
              icon-class-end="cash-coin"
              @click.prevent="goTo(
                        `${url(item.id)}/paid`,
                        `Salut Maman, est-ce que ${item.user} à bien payé la réservation ${item.reservationNumber} ?`
                        )"
      />
      <Button v-if="canBeDeleted(item)"
              label="Delete"
              color-class="danger"
              class="my-1"
              icon-class-end="trash"
              @click.prevent="goTo(
                        `${url(item.id)}/delete`,
                        `Salut Maman, tu veux vraiment supprimer la reservation de ${item.user} ?`
                        )"
      />
      <Button
          v-if="canBeArchived(item)"
          label="Archive"
          color-class="warning"
          class="my-1"
          icon-class-end="archive"
          @click.prevent="goTo(
                  `${url(item.id)}/archive`,
                  `Salut Maman, tu veux vraiment archiver la reservation de ${item.user} ?`
              )"
      />
    </template>
  </VDatatable>
</template>

<style scoped>

</style>