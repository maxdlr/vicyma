<script setup>
import Dropdown from "../components/molecule/Dropdown.vue";
import {ref} from "vue";
import {useObjectFormatter} from "../composable/formatter/object";
import DataInfo from "../components/atom/DataInfo.vue";
import Button from "../components/atom/Button.vue";

const props = defineProps({
  filters: {type: Array, required: true},
  filterProperty: {type: String, required: true},
  filterDefault: {type: String, required: true},

  items: {type: Array, required: true},
  itemsFilterByProperty: {type: String, required: true},

  label: {type: String, required: true}
});

const {getPropertyValue} = useObjectFormatter();

const selectedOption = ref(props.filterDefault);
const hasSelection = ref(false);
const isLoading = ref(false)

const canBeConfirmed = (object) => {
  return getPropertyValue(object, props.itemsFilterByProperty) === 'PENDING'
}

const canBeArchived = (object) => {
  return getPropertyValue(object, props.itemsFilterByProperty) === 'CONFIRMED' || getPropertyValue(object, props.itemsFilterByProperty) === 'PENDING'
}

const canBeDeleted = (object) => {
  return getPropertyValue(object, props.itemsFilterByProperty) === 'PENDING' || getPropertyValue(object, props.itemsFilterByProperty) === 'CONFIRMED' || getPropertyValue(object, props.itemsFilterByProperty) === 'ARCHIVED'
}

const goTo = (url, confirmMsg = null) => {
  if (confirmMsg) confirm(confirmMsg)
  location.href = url
}
</script>

<template>
  <div v-for="(filter, index) in filters" :key="index">
    <Dropdown
        :options="filter"
        :label="label"
        :option-property="getPropertyValue(filter, filterProperty)"
        placeholder="filter"
        v-model:selected-option="selectedOption"
        v-model:has-selection="hasSelection"
        v-model:is-loading="isLoading"
        sort-order-by="name"
        :simplify="true"
        :searchable="false"
        height="50"
    />
  </div>


  <div v-for="(item, index) in items" :key="index" class="">
    <div v-if="getPropertyValue(item, itemsFilterByProperty) === selectedOption"
         class="row my-2 justify-content-center align-items-start border border-2 rounded-4 border-primary my-1 px-2 py-3">
      <DataInfo :item="item" :exclude="itemsFilterByProperty" class="col"/>
      <div class="d-flex flex-column justify-content-center col-1">
        <Button v-if="canBeConfirmed(item)"
                label="Confirm"
                size="sm"
                class="my-1"
                color-class="success"
                @click.prevent="goTo(
                    `/admin/reservation/${item.id}/confirm`,
                    `Salut Maman, tu veux vraiment confirmer la reservation de ${item.user} ?`
                    )"
        />
        <Button v-if="canBeDeleted(item)"
                label="Delete"
                size="sm"
                color-class="danger"
                class="my-1"
        />
        <Button v-if="canBeArchived(item)" label="Archive" size="sm" color-class="warning" class="my-1"/>
      </div>
    </div>
  </div>
</template>
