<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";
import Button from "../../../components/atom/VButton.vue";
import {getPropertyValue} from "../../../composable/formatter/object";
import {goTo} from "../../../composable/action/redirect";

const props = defineProps({
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
  <VDatatable :data="data"
              :date-filter="{label: 'check in', codeName: 'arrivalDate'}"
              :exclude-from-row-properties="['id', 'reservationStatus']"
              :exclude-order-bys="['lodgings']"
              :new-item-link="`${baseUrl}/new`"
              :searchable-properties="['user', 'lodgings', 'reservationNumber']"
              :title="title"
              admin
              main-filter="reservationStatus"
  >
    <template #buttons="{item}">
      <Button
          class="my-1"
          color-class="primary"
          icon-class-end="box-arrow-up-right"
          label="Détails..."
          @click.prevent="goTo(`${url(item.id)}/show`)"/>
      <Button v-if="canBeConfirmed(item)"
              class="my-1"
              color-class="success"
              icon-class-end="check-all"
              label="Confirm"
              @click.prevent="goTo(
                        `${url(item.id)}/confirm`,
                        `Salut Maman, tu veux vraiment confirmer la reservation de ${item.user.value} ?`
                        )"
      />
      <Button v-if="canBePaid(item)"
              class="my-1"
              color-class="outline-success"
              icon-class-end="cash-coin"
              label="Set as paid"
              @click.prevent="goTo(
                        `${url(item.id)}/paid`,
                        `Salut Maman, est-ce que ${item.user.value} à bien payé la réservation ${item.reservationNumber} ?`
                        )"
      />
      <Button v-if="canBeDeleted(item)"
              class="my-1"
              color-class="danger"
              icon-class-end="trash"
              label="Delete"
              @click.prevent="goTo(
                        `${url(item.id)}/delete`,
                        `Salut Maman, tu veux vraiment supprimer la reservation de ${item.user.value} ?`
                        )"
      />
      <Button
          v-if="canBeArchived(item)"
          class="my-1"
          color-class="warning"
          icon-class-end="archive"
          label="Archive"
          @click.prevent="goTo(
                  `${url(item.id)}/archive`,
                  `Salut Maman, tu veux vraiment archiver la reservation de ${item.user.value} ?`
              )"
      />
    </template>
  </VDatatable>
</template>

<style scoped>

</style>