<script setup>
import { toTitle } from "../../composable/formatter/string";
const props = defineProps({
  label: { type: String, required: true, default: "label" },
  padding: { type: String, default: "ps-4 pe-2" },
});
</script>

<template>
  <div class="rounded-pill bg-transparent border border-1 border-info">
    <div :class="padding">
      <slot />
    </div>
  </div>
</template>

<style scoped></style>
