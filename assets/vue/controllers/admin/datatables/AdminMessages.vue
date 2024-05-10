<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";
import Button from "../../../components/atom/Button.vue";
import {goTo} from "../../../composable/action/redirect";

defineProps({
  name: {type: String, required: true},
  settings: {type: Object, required: true},
  items: {type: Object, required: true},
});

const baseUrl = '/admin/message';

const url = (id) => {
  return `${baseUrl}/${id}`
}

</script>

<template>
  <VDatatable
      :title="name"
      :settings="settings"
      :items="items"
      :searchable-properties="['user', 'subject', 'content', 'lodging', 'reservation']"
      :exclude-from-row-properties="['id']"
  >
    <template #buttons="{item}">
      <Button
          label="Détails..."
          class="my-1"
          @click.prevent="goTo(`${url(item.id)}/show`)"
          icon-class-end="box-arrow-up-right"
      />
      <Button
          label="Delete"
          color-class="danger"
          class="my-1"
          icon-class-end="trash"
          @click.prevent="goTo(
                        `${url(item.id)}/delete`,
                        `Salut Maman, tu veux vraiment supprimer le message de ${item.user} ?`
                        )"
      />
    </template>
  </VDatatable>
</template>

<style scoped>

</style>