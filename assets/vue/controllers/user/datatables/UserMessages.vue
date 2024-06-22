<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";
import {goTo} from "../../../composable/action/redirect";
import Button from "../../../components/atom/VButton.vue";

const props = defineProps({
  data: {type: Object, required: true},
  title: {type: String},
})

const baseUrl = '/user/message';
const url = (id) => `${baseUrl}/${id}`;

</script>

<template>
  <VDatatable
      :searchable-properties="['subject', 'content', 'lodging', 'reservation']"
      :exclude-from-row-properties="['id', 'conversation', 'user']"
      :date-filter="{label: 'sent on', codeName: 'createdOn'}"
      :data="data"
      :title="title"
      hide-empty
  >
    <template #titleButtons>
      <Button
          label="Send us a message"
          icon-class-start="send-plus-fill"
          data-bs-toggle="modal"
          data-bs-target="#userNewMessage"
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
    </template>
  </VDatatable>
</template>