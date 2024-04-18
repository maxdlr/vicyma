<script setup>
import {
  COLOR_CLASSES,
} from "../../constant/bootstrap-constants";
import {onMounted, onUnmounted, ref} from "vue";

const props = defineProps({
  label: {type: String, default: "label", required: true},
  options: {
    type: Array,
    required: true,
  },

  mainColorClass: {
    type: String,
    default: "primary",
    validator(value) {
      return COLOR_CLASSES.includes(value);
    },
  },
  successColorClass: {
    type: String,
    default: "success",
    validator(value) {
      return COLOR_CLASSES.includes(value);
    },
  },
  height: {type: [Number, String], default: 200, required: false},
  catEmoji: {type: String, default: "⚡"},
});
const selectedOption = defineModel("selectedOption", {required: true});

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
    <select v-model="selectedOption" name="" :id="`select-${label}`" class="form-select form-control">
      <option v-for="(option, index) in options" :key="index">{{ option.name }}</option>
    </select>
    <label for="`select-${label}`">{{ label }}</label>
  </div>
</template>