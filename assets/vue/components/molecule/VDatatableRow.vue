<script setup>
import { onBeforeMount, onMounted, ref } from "vue";
import VDatatableCell from "../atom/VDatatableCell.vue";
import VYoyo from "./VYoyo.vue";
import { toTitle } from "../../composable/formatter/string";

const props = defineProps({
  item: { type: Object, required: true },
  excludeProperties: { type: Array },
  admin: { type: Boolean, default: false, required: false },
  hideEmpty: { type: Boolean, default: false },
  maxCellCountInRow: { type: Number },
});

const mainRowItem = ref({});
const hovering = ref(false);

const datatableRow = ref(null);
const cellCount = ref(0);

onMounted(() => {
  cellCount.value = datatableRow.value.children.length;
});

const repositionItems = (properties) => {
  for (const property of properties) {
    if (mainRowItem.value[property]) {
      delete mainRowItem.value[property];
    }
  }

  if (mainRowItem.value["rate"] && mainRowItem.value["lodging"]) {
    delete mainRowItem.value["lodging"];
  }

  for (const key in mainRowItem.value) {
    if (
      (!mainRowItem.value[key] ||
        (typeof mainRowItem.value[key] === "object" &&
          Object.keys(mainRowItem.value[key]).length === 0)) &&
      props.hideEmpty
    ) {
      delete mainRowItem.value[key];
    }
  }
};

onBeforeMount(() => {
  for (const property in props.item) {
    if (props.excludeProperties) {
      if (!props.excludeProperties.includes(property)) {
        mainRowItem.value[property] = props.item[property];
      }
    } else {
      mainRowItem.value[property] = props.item[property];
    }
  }

  repositionItems([
    "createdOn",
    "firstname",
    "lastname",
    "reservationNumber",
    "arrivalDate",
    "departureDate",
    "rate",
    "reservationStatus",
  ]);

  // if (mainRowItem.value["createdOn"]) {
  //   delete mainRowItem.value["createdOn"];
  // }
  //
  // if (mainRowItem.value["firstname"]) {
  //   delete mainRowItem.value["firstname"];
  // }
  //
  // if (mainRowItem.value["lastname"]) {
  //   delete mainRowItem.value["lastname"];
  // }
  //
  // if (mainRowItem.value["reservationNumber"]) {
  //   delete mainRowItem.value["reservationNumber"];
  // }
  //
  // if (mainRowItem.value["arrivalDate"]) {
  //   delete mainRowItem.value["arrivalDate"];
  // }
  //
  // if (mainRowItem.value["departureDate"]) {
  //   delete mainRowItem.value["departureDate"];
  // }
  //
  //
  // if (mainRowItem.value["rate"]) {
  //   delete mainRowItem.value["rate"];
  // }
  //
  // if (mainRowItem.value["reservationStatus"]) {
  //   delete mainRowItem.value["reservationStatus"];
  // }
});
</script>

<template>
  <div v-if="$slots.buttons">
    <VYoyo :is-open="hovering" direction="down-left" label="Action">
      <template #buttons>
        <slot name="buttons" />
      </template>
    </VYoyo>
  </div>

  <div
    :class="hovering ? 'border-secondary' : 'border-secondary-subtle'"
    class="my-2 border border-2 rounded-4 p-3"
    @mouseenter="hovering = true"
    @mouseleave="hovering = false"
  >
    <div class="d-flex justify-content-between align-items-center">
      <div class="d-flex justify-content-center align-items-center">
        <slot :item="item" name="rowHeader" />

        <div v-if="item.rate">
          <span class="badge bg-success fw-bold fs-5 rounded-pill me-3">{{
            item.rate
          }}</span>
          <a
            :href="`/admin/lodging/${item.lodging.id}/show`"
            class="fs-5 fw-bold icon-link icon-link-hover"
          >
            {{ item.lodging.value }}
            <i class="bi bi-arrow-right-short fs-3"></i>
          </a>
        </div>

        <div
          v-if="item.reservationNumber"
          :class="hovering ? 'text-secondary-emphasis' : 'text-secondary'"
          class="d-flex justify-content-center align-items-center"
        >
          <span
            v-if="
              item.reservationStatus &&
              ['PENDING', 'CONFIRMED'].includes(item.reservationStatus)
            "
            :class="[
              ['PENDING'].includes(item.reservationStatus)
                ? hovering
                  ? 'bg-warning'
                  : 'bg-warning-subtle text-warning'
                : '',
              ['CONFIRMED'].includes(item.reservationStatus)
                ? hovering
                  ? 'bg-success'
                  : 'bg-success-subtle text-success'
                : '',
            ]"
            class="me-3 badge fs-6 rounded-pill fw-bold"
          >
            {{ item.reservationStatus }}
          </span>
          <span class="fs-5 fw-bold">{{ item.reservationNumber }}</span>
          <div
            :class="hovering ? 'bg-info' : 'bg-info-subtle text-info'"
            class="d-inline badge ms-3 fs-6 rounded-pill"
          >
            <span v-if="item.arrivalDate" class="fw-bold"
              >{{ item.arrivalDate }}
            </span>
            <i class="bi bi-arrow-right mx-3"></i>
            <span v-if="item.departureDate" class="fw-bold"
              >{{ item.departureDate }}
            </span>
          </div>
        </div>

        <div
          v-if="item.firstname || item.lastname"
          :class="hovering ? 'text-secondary-emphasis' : 'text-secondary'"
        >
          <span class="fs-5 fw-bold">{{ toTitle(item.firstname) + " " }}</span>
          <span class="fs-5 fw-bold">{{ toTitle(item.lastname) }}</span>
        </div>
      </div>
      <div
        v-if="item.createdOn"
        :class="hovering ? 'bg-secondary' : 'bg-secondary-subtle'"
        class="badge fs-6 rounded-pill text-end"
      >
        {{ item.createdOn }}
      </div>
    </div>
    <div
      ref="datatableRow"
      :class="`row-cols-${cellCount > maxCellCountInRow ? Math.round(cellCount / 2) : cellCount}`"
      class="row"
    >
      <div v-for="(property, index) in mainRowItem" :key="index" class="p-2">
        <slot :item="{ property, index }" name="cell">
          <VDatatableCell :admin="admin" :name="index" :value="property" />
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
