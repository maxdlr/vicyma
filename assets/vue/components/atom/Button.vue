<script setup>
defineProps({
  label: {type: String, default: null},
  colorClass: {type: String, default: "primary"},
  size: {type: String, default: null},
  roundClass: {type: String, default: null},
  iconClassStart: {type: String, default: null},
  iconClassEnd: {type: String, default: null},
});
</script>

<template>
  <button
      class="btn border-0 text-nowrap"
      :class="[
      `btn-${colorClass}`,
      !size ? '' : `btn-${size}`,
      roundClass ? `rounded-${roundClass}` : '',
    ]"
  >
    <slot name="iconStart">
      <i :class="`bi bi-${iconClassStart}`"></i>
    </slot>
    <span v-if="label" :class="[
        $slots['iconStart'] || iconClassStart ? 'ps-2' : '',
        $slots['iconEnd'] || iconClassEnd ? 'pe-2' : ''
          ]">
      {{ label }}
    </span>
    <slot name="iconEnd">
      <i :class="`bi bi-${iconClassEnd}`"></i>
    </slot>
  </button>
</template>
