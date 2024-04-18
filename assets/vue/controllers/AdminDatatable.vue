<script setup>
import Dropdown from "../components/molecule/Dropdown.vue";
import {computed, ref} from "vue";
import {useStringFormatter} from "../composable/formatter/string";
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

  <div v-for="(item, index) in items" :key="index">
    <div v-if="getPropertyValue(item, itemsFilterByProperty) === selectedOption" class="row my-2">
      <DataInfo :item="item" :exclude="itemsFilterByProperty" class="col"/>
      <Button v-if="canBeConfirmed(item)" class="col-1" label="Confirm"
              color-class="success"/>
      <Button v-if="canBeDeleted(item)" class="col-1" label="Delete" color-class="danger"/>
      <Button v-if="canBeArchived(item)" class="col-1" label="Archive" size="sm" color-class="warning"/>
    </div>
  </div>
</template>
