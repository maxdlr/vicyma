<script setup>
import VDatatable from "../components/organism/VDatatable.vue";
import Button from "../components/atom/Button.vue";
import {goTo} from "../composable/action/redirect";

defineProps({
  settings: {type: Object, required: true},
  items: {type: Object, required: true},
});

const baseUrl = '/admin/user';

const url = (id) => {
  return `${baseUrl}/${id}`
}

</script>

<template>
  <VDatatable
      title="Clients"
      :settings="settings"
      :items="items"
      :searchable-properties="['firstname', 'lastname', 'reservations', 'email', 'phoneNumber']"
      :exclude-filters="['firstname', 'lastname']"
      :exclude-from-row-properties="['id']"
  >
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
                        `Salut Maman, tu veux vraiment supprimer ${item.firstname} ${item.lastname} ?`
                        )"
      >
        <template #iconEnd><i class="bi bi-trash"></i></template>
      </Button>
    </template>
  </VDatatable>
</template>

<style scoped>

</style>