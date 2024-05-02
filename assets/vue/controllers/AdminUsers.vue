<script setup>
import VDatatable from "../components/organism/VDatatable.vue";
import Button from "../components/atom/Button.vue";
import {useObjectFormatter} from "../composable/formatter/object";
import {goTo} from "../composable/action/redirect";

const {getPropertyValue} = useObjectFormatter();

defineProps({
  filters: {type: Object, required: true},
  items: {type: Object, required: true},
});

const baseUrl = '/admin/user';

const url = (id) => {
  return `${baseUrl}/${id}`
}

</script>

<template>
  <VDatatable
      :filters="filters"
      :items="items"
      :exclude-from-row-properties="['id', 'reservationStatus']"
      :searchable-properties="['user', 'lodgings']"
      title="Reservation requests">
    <template #buttons="{item}">
      <Button
          label="Détails..."
          class="my-1"
          @click.prevent="goTo(`${url(item.id)}/show`)">
        <template #iconEnd><i class="bi bi-box-arrow-up-right"></i></template>
      </Button>
      <Button
          label="Delete"
          color-class="danger"
          class="my-1"
          @click.prevent="goTo(
                        `${url(item.id)}/delete`,
                        `Salut Maman, tu veux vraiment supprimer la reservation de ${item.user} ?`
                        )"
      >
        <template #iconEnd><i class="bi bi-trash"></i></template>
      </Button>
    </template>
  </VDatatable>
</template>

<style scoped>

</style>