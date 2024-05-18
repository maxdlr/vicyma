<script setup>
import VDatatable from "../../../components/organism/VDatatable.vue";
import Button from "../../../components/atom/Button.vue";
import {goTo} from "../../../composable/action/redirect";
import {getPropertyValue} from "../../../composable/formatter/object";

defineProps({
  data: {type: Object, required: true},
  title: {type: String},
});

const baseUrl = '/admin/user';

const url = (id) => `${baseUrl}/${id}`;
const canBeDeleted = (object) => !getPropertyValue(object, 'isDeleted');

</script>

<template>
  <VDatatable
      :title="title"
      :data="data"
      :searchable-properties="['firstname', 'lastname', 'reservations', 'email', 'phoneNumber']"
      :exclude-filters="['firstname', 'lastname']"
      :exclude-from-row-properties="['id', 'isDeleted', 'createdOn', 'roles']"
      :new-item-link="`${baseUrl}/new`"
      :date-filter="{label: 'member since', codeName: 'createdOn'}"
  >
    <template #buttons="{item}">
      <Button
          label="Détails..."
          color-class="primary"
          class="my-1"
          @click.prevent="goTo(`${url(item.id)}/show`)"
          icon-class-end="box-arrow-up-right"
      />
      <Button
          v-if="canBeDeleted(item)"
          label="Delete"
          color-class="danger"
          class="my-1"
          icon-class-end="trash"
          @click.prevent="goTo(
                        `${url(item.id)}/delete`,
                        `Salut Maman, tu veux vraiment supprimer le compte de ${item.firstname} ${item.lastname} ?`
                        )"
      />
    </template>
  </VDatatable>
</template>

<style scoped>

</style>