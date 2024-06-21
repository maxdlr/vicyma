<script setup>
import {onBeforeMount, onMounted, ref} from "vue";
import VDatatableCell from "../atom/VDatatableCell.vue";
import VYoyo from "./VYoyo.vue";
import {toTitle} from "../../composable/formatter/string";

const props = defineProps({
  item: {type: Object, required: true},
  excludeProperties: {type: Array},
  admin: {type: Boolean, default: false, required: false},
  hideEmpty: {type: Boolean, default: false}
})

const mainRowItem = ref({})
const hovering = ref(false)

const datatableRow = ref(null)
const cellCount = ref(0)

onMounted(() => {
  cellCount.value = datatableRow.value.children.length
})

onBeforeMount(() => {
  for (const property in props.item) {
    if (props.excludeProperties) {
      if (!props.excludeProperties.includes(property)) {
        mainRowItem.value[property] = props.item[property]
      }
    } else {
      mainRowItem.value[property] = props.item[property]
    }
  }

  if (mainRowItem.value['createdOn']) {
    delete mainRowItem.value['createdOn']
  }

  if (mainRowItem.value['firstname']) {
    delete mainRowItem.value['firstname']
  }

  if (mainRowItem.value['lastname']) {
    delete mainRowItem.value['lastname']
  }

  if (mainRowItem.value['reservationNumber']) {
    delete mainRowItem.value['reservationNumber']
  }

  if (mainRowItem.value['arrivalDate']) {
    delete mainRowItem.value['arrivalDate']
  }

  if (mainRowItem.value['departureDate']) {
    delete mainRowItem.value['departureDate']
  }

  if (mainRowItem.value['rate'] && mainRowItem.value['lodging']) {
    delete mainRowItem.value['lodging']
  }

  if (mainRowItem.value['rate']) {
    delete mainRowItem.value['rate']
  }

  if (mainRowItem.value['reservationStatus']) {
    delete mainRowItem.value['reservationStatus']
  }

  for (const key in mainRowItem.value) {
    if (!mainRowItem.value[key] && props.hideEmpty) {
      delete mainRowItem.value[key]
    }
  }
})
</script>

<template>
  <div v-if="$slots.buttons">
    <VYoyo label="Action" direction="down-left" :is-open="hovering">
      <template #buttons>
        <slot name="buttons"/>
      </template>
    </VYoyo>
  </div>

  <div class="my-2 border border-2 rounded-4 p-3"
       :class="hovering ? 'border-secondary' : 'border-secondary-subtle'"
       @mouseenter="hovering = true"
       @mouseleave="hovering = false"
  >
    <div class="d-flex justify-content-between align-items-center">
      <div class="d-flex justify-content-center align-items-center">

        <slot name="rowHeader" :item="item" />

        <div v-if="item.rate">
          <span class="badge bg-success fw-bold fs-5 rounded-pill me-3">{{ item.rate }}</span>
          <a class="fs-5 fw-bold icon-link icon-link-hover"
             :href="`/admin/lodging/${item.lodging.id}/show`">
            {{ item.lodging.value }}
            <i class="bi bi-arrow-right-short fs-3"></i>
          </a>
        </div>

        <div v-if="item.reservationNumber" class="d-flex justify-content-center align-items-center"
             :class="hovering ? 'text-secondary-emphasis' : 'text-secondary'">
          <span class="me-3 badge fs-6 rounded-pill fw-bold"
                :class="[
                    ['PENDING'].includes(item.reservationStatus) ? hovering ? 'bg-warning' : 'bg-warning-subtle text-warning' : '',
                    ['CONFIRMED'].includes(item.reservationStatus) ? hovering ? 'bg-success' : 'bg-success-subtle text-success' : '',
                ]"
                v-if="item.reservationStatus && ['PENDING', 'CONFIRMED'].includes(item.reservationStatus)">
            {{ item.reservationStatus }}
          </span>
          <span class="fs-5 fw-bold">{{ item.reservationNumber }}</span>
          <div class="d-inline badge ms-3 fs-6 rounded-pill"
               :class="hovering ? 'bg-info' : 'bg-info-subtle text-info'">
            <span class="fw-bold" v-if="item.arrivalDate">{{ item.arrivalDate }} </span>
            <i class="bi bi-arrow-right mx-3"></i>
            <span class="fw-bold" v-if="item.departureDate">{{ item.departureDate }} </span>
          </div>
        </div>

        <div v-if="item.firstname || item.lastname" :class="hovering ? 'text-secondary-emphasis' : 'text-secondary'">
          <span class="fs-5 fw-bold">{{ toTitle(item.firstname) + ' ' }}</span>
          <span class="fs-5 fw-bold">{{ toTitle(item.lastname) }}</span>
        </div>
      </div>
      <div class="badge fs-6 rounded-pill text-end" :class="hovering ? 'bg-secondary' : 'bg-secondary-subtle'"
           v-if="item.createdOn">
        {{ item.createdOn }}
      </div>
    </div>
    <div class="row"
         :class="`row-cols-${cellCount > 4 ? Math.round(cellCount / 2) : cellCount}`"
         ref="datatableRow"
    >
      <div v-for="(property, index) in mainRowItem" :key="index" class="p-2">
        <slot name="cell" :item="{property, index}">
          <VDatatableCell
              :name="index"
              :value="property"
              :admin="admin"
          />
        </slot>
      </div>
    </div>
  </div>
</template>

<style scoped>

.icon-link > .bi {
  height: unset !important;
}
</style>