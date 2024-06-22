<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";
import {goTo} from "../../../composable/action/redirect";
import Button from "../../../components/atom/VButton.vue";
import {singularize} from "../../../composable/formatter/string";

const props = defineProps({
  data: {type: Object, required: true},
  title: {type: String},
})

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
      :exclude-from-row-properties="['id', 'messages', 'isArchivedByUser']"
      :data="data"
      :title="title"
  >
    <template #titleButtons>
      <Button
          label="Send us a message"
          icon-class-start="send-plus-fill"
          data-bs-toggle="modal"
          data-bs-target="#userNewMessage"
      />
    </template>
    <template #rowHeader="{item}">
      <span
          class="badge fw-bold fs-5 rounded-pill me-3"
          :class="unreadMessages(item.item.messages).length > 0 ? 'bg-success' : 'bg-secondary'"
      >
        {{ unreadMessages(item.item.messages).length }} new {{
          singularize('messages', unreadMessages(item.item.messages))
        }}
      </span>
    </template>
    <template #buttons="{item}">
      <Button
          label="Open"
          class="my-1"
          color-class="primary"
          @click.prevent="goTo(`/user/message/${item.messages[0].id}/show`)"
          icon-class-end="box-arrow-up-right"
      />
      <Button
          label="Archive"
          class="my-1"
          color-class="warning"
          icon-class-end="box-fill"
          @click.prevent="goTo(`/user/conversation/${item.id}/archive`)"
      />
    </template>
  </VDatatable>
</template>
