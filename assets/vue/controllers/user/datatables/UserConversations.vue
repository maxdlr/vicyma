<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";
import {goTo} from "../../../composable/action/redirect";
import Button from "../../../components/atom/VButton.vue";
import {singularize} from "../../../composable/formatter/string";

const props = defineProps({
  data: {type: Object, required: true},
  title: {type: String},
})

const baseUrl = '/user/conversation';
const url = (id) => `${baseUrl}/${id}`;

const unreadMessages = (messageCollection) => {
  return messageCollection.filter(
      (message) => {
        return !message.isReadByUser && !message.user
      }
  );
}

</script>

<template>
  <VDatatable
      :searchable-properties="['messages']"
      :exclude-from-row-properties="['id', 'messages']"
      :data="data"
      :title="title"
  >
    <template #rowHeader="{item}">
      <span class="badge bg-success fw-bold fs-5 rounded-pill me-3">
        {{unreadMessages(item.item.messages).length}} new {{ singularize(unreadMessages(item.item.messages), 'messages') }}
      </span>
    </template>
    <template #buttons="{item}">
      <Button
          label="Open"
          class="my-1"
          color-class="primary"
          @click.prevent="goTo(`${url(item.id)}/show`)"
          icon-class-end="box-arrow-up-right"
      />
    </template>
  </VDatatable>
</template>

<style scoped>

</style>