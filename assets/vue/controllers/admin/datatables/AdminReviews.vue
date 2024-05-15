<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";
import Button from "../../../components/atom/Button.vue";
import {goTo} from "../../../composable/action/redirect";

defineProps({
  data: {type: Object, required: true},
  title: {type: String},
});

const baseUrl = '/admin/message';

const url = (id) => `${baseUrl}/${id}`;

</script>

<template>
  <VDatatable
      :title="title"
      :data="data"
      :searchable-properties="['user', 'rate', 'comment', 'lodging']"
      :exclude-from-row-properties="['id']"
      main-filter="rate"
      :date-filter="{label: 'member since', codeName: 'createdOn'}"
  >
    <template #buttons="{item}">
      <Button
          label="Détails..."
          color-class="secondary"
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
                        `Salut Maman, tu veux vraiment supprimer la recommandation de  ${item.user} ?`
                        )"
      />
    </template>
  </VDatatable>
</template>

<style scoped>

</style>