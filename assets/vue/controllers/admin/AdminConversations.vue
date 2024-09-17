<script setup>
import VDatatable from "../../components/organism/VDatatable.vue";
import { goTo } from "../../composable/action/redirect";
import Button from "../../components/atom/Button.vue";

const props = defineProps({
  conversations: { type: Object, required: true },
});

const baseUrl = "/admin/conversation";

const url = (id) => `${baseUrl}/${id}`;
</script>

<template>
  <VDatatable
    :data="conversations.data"
    :date-filter="{ label: 'creation date', codeName: 'createdOn' }"
    :exclude-from-row-properties="['id']"
    :searchable-properties="['conversationId', 'user']"
    admin
    hide-order-by
    title="Conversations"
  >
    <template #titleButtons>
      <Button
        class="my-1"
        color-class="outline-primary"
        icon-class-end="box-arrow-up-right"
        label="See all messages"
        @click.prevent="goTo('/admin/business#messages')"
      />
    </template>
    <template #buttons="{ item }">
      <Button
        class="my-1"
        color-class="primary"
        icon-class-end="box-arrow-up-right"
        label="Open"
        @click.prevent="goTo(`${url(item.id)}/show`)"
      />
      <Button
        class="my-1"
        color-class="danger"
        icon-class-end="trash-fill"
        label="Delete"
        @click.prevent="
          goTo(
            `${url(item.id)}/delete`,
            `Tous les messages de cette conversation seront supprimés, ${item.user.value} n\'y aura plus accès non plus, t'es sure ?`,
          )
        "
      />
    </template>
  </VDatatable>
</template>

<style scoped></style>
