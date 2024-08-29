<script setup>
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { onMounted, ref } from "vue";
import { addDays, isEqual, set } from "date-fns";
import InputWrapper from "./InputWrapper.vue";
import { COLOR_CLASSES } from "../../constant/bootstrap-constants";

const props = defineProps({
  label: { type: String, required: true, default: "label" },
  placeholder: { type: String, required: true, default: "placeholder" },
  availableDates: { type: Array, required: false, default: null },
  occupiedDates: { type: Array, required: false, default: null },
  range: { type: Boolean, default: false },
  mainColorClass: {
    type: String,
    default: "info",
    validator(value) {
      return COLOR_CLASSES.includes(value);
    },
  },
});

const date = defineModel("date", {
  type: [Array],
  default: [new Date(), new Date()],
});
const isDateValid = ref(null);

const dateUi = ref({
  navBtnNext: "",
  navBtnPrev: "",
  calendar: "",
  calendarCell: "",
  menu: "",
  input: "bg-transparent border border-0",
});

const getDayClass = (date, _internalDate) => {
  if (
    isEqual(
      date,
      addDays(
        set(new Date(), { hours: 0, minutes: 0, seconds: 0, milliseconds: 0 }),
        1,
      ),
    )
  )
    return "marked-cell";
  return "";
};

onMounted(() => {
  if (props.availableDates) {
    for (const availableDate of props.availableDates) {
      markers.value.push({
        date: availableDate,
        type: "dot",
        tooltip: [{ text: "Disponible !", color: "green" }],
      });
      // allowedDates.value.push(availableDate)
    }
  }
});

const markers = ref([]);
const allowedDates = ref([]);
</script>

<template>
  <InputWrapper
    :label="label"
    padding=""
    slot-container-classes="d-flex justify-content-center align-items-center"
  >
    <div class="d-inline-block">
      <VueDatePicker
        v-model="date"
        :auto-apply="true"
        :class="`rounded-4 text-${mainColorClass}`"
        :day-class="getDayClass"
        :enable-time-picker="false"
        :hide-input-icon="true"
        :markers="markers"
        :placeholder="placeholder"
        :range="range"
        :state="isDateValid"
        :ui="dateUi"
      >
        <template #marker="{ marker, day, date }">
          <span class="custom-marker"></span>
        </template>
      </VueDatePicker>
    </div>
    <i :class="`bi bi-caret-down-fill text-${mainColorClass}`" />
  </InputWrapper>
</template>

<style lang="scss" scoped>
@import "../../../styles/app";

.custom-marker {
  position: absolute;
  top: 0;
  right: 0;
  height: 8px;
  width: 8px;
  border-radius: 100%;
  background-color: green;
}

.dp__theme_light {
  --dp-text-color: $info;
}
</style>
