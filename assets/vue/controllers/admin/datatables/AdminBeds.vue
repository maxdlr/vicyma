<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";
import Button from "../../../components/atom/VButton.vue";
import {goTo} from "../../../composable/action/redirect";

defineProps({
  data: {type: Object, required: true},
  title: {type: String},
});

const baseUrl = '/admin/bed';

const url = (id) => `${baseUrl}/${id}`;
</script>

<template>
  <VDatatable admin
      :title="title"
      :data="data"
      :searchable-properties="['height', 'width']"
      :exclude-from-row-properties="['id']"
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
                        `Salut Maman, tu veux vraiment supprimer le lit ${item.width} - ${item.height} ?`
                        )"
      />
    </template>
  </VDatatable>
</template>

<style scoped>

</style>