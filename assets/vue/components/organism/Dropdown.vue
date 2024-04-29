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
  <div class="form-floating m-0">
    <select v-model="selectedOption" name="" :id="`select-${label}`" class="form-select form-control"
            @change="emit('hasSelection')">
      <option value="">All</option>
      <option v-for="(option, index) in options" :key="index">{{ option }}</option>
    </select>
    <label for="`select-${label}`">{{ label }}</label>
  </div>
</template>