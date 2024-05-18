<script setup>
import VDatatable from "../../components/organism/VDatatable.vue";
import {goTo} from "../../composable/action/redirect";
import Button from "../../components/atom/Button.vue";

const props = defineProps({
  conversations: {type: Object, required: true}
})

const baseUrl = '/admin/conversation';

const url = (id) => `${baseUrl}/${id}`;

</script>

<template>
  <VDatatable
      title="Conversations"
      :data="conversations.data"
      :searchable-properties="['conversationId', 'client']"
      :exclude-from-row-properties="['id', 'conversationId']"
      :date-filter="{label: 'creation date', codeName: 'createdOn'}"
  >
    <template #titleButtons>
      <Button
          label="See all messages"
          class="my-1"
          color-class="outline-primary"
          @click.prevent="goTo('/admin/business#messages')"
          icon-class-end="box-arrow-up-right"
      />
    </template>
    <template #buttons="{item}">
      <Button
          label="Open"
          class="my-1"
          color-class="primary"
          @click.prevent="goTo(`${url(item.id)}/show`)"
          icon-class-end="box-arrow-up-right"
      />
      <Button
          label="Delete"
          class="my-1"
          color-class="danger"
          @click.prevent="goTo(`${url(item.id)}/delete`, `Tous les messages de cette conversation seront supprimés, ${item.user.value} n\'y aura plus accès non plus, t'es sure ?`)"
          icon-class-end="trash-fill"
      />
    </template>
  </VDatatable>
</template>

<style scoped>

</style>