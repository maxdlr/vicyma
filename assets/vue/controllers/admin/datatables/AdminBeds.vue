<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";
import Button from "../../../components/atom/Button.vue";
import { goTo } from "../../../composable/action/redirect";

defineProps({
  data: { type: Object, required: true },
  title: { type: String },
});

const baseUrl = "/admin/bed";

const url = (id) => `${baseUrl}/${id}`;
</script>

<template>
  <VDatatable
    :data="data"
    :exclude-from-row-properties="['id']"
    :hide-empty="false"
    :new-item-link="`${baseUrl}/new`"
    :title="title"
    admin
  >
    <template #buttons="{ item }">
      <Button
        class="my-1"
        color-class="warning"
        icon-class-end="box-arrow-up-right"
        label="Edit"
        @click.prevent="goTo(`${url(item.id)}/show`)"
      />
      <Button
        class="my-1"
        color-class="danger"
        icon-class-end="trash"
        label="Delete"
        @click.prevent="
          goTo(
            `${url(item.id)}/delete`,
            `Salut Maman, tu veux vraiment supprimer le lit ${item.width} - ${item.height} ?`,
          )
        "
      />
    </template>
  </VDatatable>
</template>

<style scoped></style>
