<script setup>
import {
  COLOR_CLASSES,
} from "../../constant/bootstrap-constants";
import {onMounted, onUnmounted, ref} from "vue";
import {useStringFormatter} from "../../composable/formatter/string";
import {useObjectFormatter} from "../../composable/formatter/object";

const {toTitle} = useStringFormatter();
const {getPropertyValue} = useObjectFormatter()


const props = defineProps({
  label: {type: String, default: "label", required: true},
  options: {type: Array, required: true},
  noEmpty: {type: Boolean, default: false, required: false},
  propertyOf: {type: String, required: false},

  mainColorClass: {
    type: String,
    default: "",
    validator(value) {
      return COLOR_CLASSES.includes(value);
    },
  },
});

const selectedOption = defineModel('selectedOption', {required: true});

const emit = defineEmits(['hasSelection'])

const screenWidth = ref(screen.width);
const screenHeight = ref(screen.height);

const handleResize = () => {
  screenWidth.value = screen.width;
  screenHeight.value = screen.height;
};

onMounted(() => {
  window.addEventListener("resize", handleResize);
});
onUnmounted(() => {
  window.removeEventListener("resize", handleResize);
});

</script>

<template>
  <div class="form-floating">
    <select
        v-model="selectedOption"
        :id="`select-${label}`"
        class="form-select form-control"
        :class="`bg-${mainColorClass}`"
        @change="emit('hasSelection')"
    >
      <option value="" v-if="!noEmpty">All</option>
      <option v-for="(option, index) in options" :value="option" :key="index">
        {{ propertyOf ? toTitle(getPropertyValue(option, propertyOf)) : option }}
      </option>
    </select>
    <label :for="`select-${label}`">{{ toTitle(label) }}</label>
  </div>
</template>