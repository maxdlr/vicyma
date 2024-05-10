<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";
import Button from "../../../components/atom/Button.vue";
import {getPropertyValue} from "../../../composable/formatter/object";
import {goTo} from "../../../composable/action/redirect";

defineProps({
  name: {type: String, required: true},
  settings: {type: Object, required: true},
  items: {type: Object, required: true},
});

const baseUrl = '/admin/reservation';

const url = (id) => {
  return `${baseUrl}/${id}`
}

const canBeConfirmed = (object) => {
  return getPropertyValue(object, 'reservationStatus') === 'PENDING'
}

const canBeArchived = (object) => {
  return getPropertyValue(object, 'reservationStatus') === 'CONFIRMED' ||
      getPropertyValue(object, 'reservationStatus') === 'PENDING'
}

const canBeDeleted = (object) => {
  return getPropertyValue(object, 'reservationStatus') === 'PENDING' ||
      getPropertyValue(object, 'reservationStatus') === 'CONFIRMED' ||
      getPropertyValue(object, 'reservationStatus') === 'ARCHIVED'
}

</script>

<template>
  <VDatatable
      :title="name"
      :settings="settings"
      :items="items"
      main-filter="reservationStatus"
      :exclude-from-row-properties="['id', 'reservationStatus']"
      :searchable-properties="['user', 'lodgings', 'reservationNumber']"
  >
    <template #buttons="{item}">
      <Button
          label="Détails..."
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