<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";
import Button from "../../../components/atom/Button.vue";
import {goTo} from "../../../composable/action/redirect";
import {toTitle} from "../../../composable/formatter/string";

defineProps({
  data: {type: Object, required: true},
  title: {type: String},
});

const baseUrl = '/admin/lodging';

const url = (id) => `${baseUrl}/${id}`;
</script>

<template>
  <VDatatable
      :title="title"
      :data="data"
      :searchable-properties="['name']"
      :exclude-from-row-properties="['id']"
      :exclude-filters="['name', 'priceByNight', 'reviews']"
      :exclude-order-bys="['reviews']"
      :new-item-link="`${baseUrl}/new`"
  >
    <template #buttons="{item}">
      <Button
          label="Edit"
          class="my-1"
          color-class="warning"
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
                        `Salut Maman, tu veux vraiment supprimer l'appartement ${toTitle(item.name)} ?`
                        )"
      />
    </template>
  </VDatatable>
</template>

<style scoped>

</style>