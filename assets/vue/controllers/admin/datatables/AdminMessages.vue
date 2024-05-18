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
      :searchable-properties="['user', 'subject', 'content', 'lodging', 'reservation']"
      :exclude-from-row-properties="['id', 'content']"
      :date-filter="{label: 'reception date', codeName: 'createdOn'}"
  >
    <template #titleButtons>
      <Button
          label="See conversations"
          class="my-1"
          color-class="outline-primary"
          @click.prevent="goTo('/admin/conversations')"
          icon-class-end="box-arrow-up-right"
      />
    </template>
    <template #buttons="{item}">
      <Button
          label="Open conversation"
          class="my-1"
          color-class="primary"
          @click.prevent="goTo(`${url(item.id)}/reply`)"
          icon-class-end="box-arrow-up-right"
      />
      <Button
          label="Delete this message"
          color-class="danger"
          class="my-1"
          icon-class-end="trash"
          @click.prevent="goTo(
                        `${url(item.id)}/delete`,
                        `Salut Maman, tu veux vraiment supprimer le message de ${item.user.value} ?`
                        )"
      />
    </template>
  </VDatatable>
</template>

<style scoped>

</style>