<script setup>
import UserMessages from "./datatables/UserMessages.vue";
import UserConversations from "./datatables/UserConversations.vue";
import TabbedDatatable from "../../components/molecule/TabbedDatatable.vue";

defineProps({
  datatables: {type: Object, required: true}
})

</script>

<template>
  <TabbedDatatable
      default-tab="conversations"
      :components="{ messages: UserMessages, conversations: UserConversations }"
      :datatables="datatables"
  />
</template>