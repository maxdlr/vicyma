<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import {onMounted, ref} from "vue";
import { addDays, isEqual, set } from 'date-fns';
import InputWrapper from "./InputWrapper.vue";

const props = defineProps({
  label: {type: String, required: true, default: 'label'},
  placeholder: {type: String, required: true, default: 'placeholder'},
  availableDates: {type: Array, required: false, default: null},
  occupiedDates: {type: Array, required: false, default: null},
  range: {type: Boolean, default: false}
})

const date = defineModel('date', {type: [Array], default: [new Date(), new Date()]})
const isDateValid = ref(null);

const dateUi = ref(
    {
      navBtnNext: '',
      navBtnPrev: '',
      calendar: '',
      calendarCell: '',
      menu: '',
      input: 'bg-transparent border border-0',
    }
)

const getDayClass = (date, _internalDate) => {
  if (isEqual(date, addDays(set(new Date(), { hours: 0, minutes: 0, seconds: 0, milliseconds: 0 }), 1)))
    return 'marked-cell';
  return '';
};

onMounted(() => {
  if (props.availableDates) {
    for (const availableDate of props.availableDates) {
      markers.value.push(
          {
            date: availableDate,
            type: 'dot',
            tooltip: [{ text: 'Disponible !', color: 'green' }],
          }
      )
      // allowedDates.value.push(availableDate)
    }
  }
})

const markers = ref([])
const allowedDates = ref([])

</script>

<template>
  <InputWrapper :label="label">
    <VueDatePicker
        v-model="date"
        :day-class="getDayClass"
        :state="isDateValid"
        :ui="dateUi"
        class="rounded-4"
        :markers="markers"
        :range="range"
        :enable-time-picker="false"
        :placeholder="placeholder"
        :auto-apply="true"
        :hide-input-icon="true"
    >
      <template #marker="{ marker, day, date }">
        <span class="custom-marker"></span>
      </template>
    </VueDatePicker>
  </InputWrapper>
</template>

<style scoped lang="scss">
.custom-marker {
  position: absolute;
  top: 0;
  right: 0;
  height: 8px;
  width: 8px;
  border-radius: 100%;
  background-color: green;
}
</style>