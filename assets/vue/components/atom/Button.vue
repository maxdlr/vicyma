<script setup>
defineProps({
  label: { type: String, default: null },
  colorClass: { type: String, default: "primary" },
  size: { type: String, default: null },
  roundClass: { type: String, default: null },
});
</script>

<template>
  <button
    class="btn border-0"
    :class="[
      `btn-${colorClass}`,
      !size ? '' : `btn-${size}`,
      roundClass ? `rounded-${roundClass}` : '',
    ]"
  >
    <slot name="icon" />
    <span v-if="label">
      {{ label }}
    </span>
  </button>
</template>
