<script setup>
defineProps({
  label: {type: [String, Number, Boolean], default: null},
  colorClass: {type: String, default: "primary"},
  size: {type: String, default: null},
  roundClass: {type: String, default: 'pill'},
  iconClassStart: {type: String, default: null},
  iconClassEnd: {type: String, default: null},
});
</script>

<template>
  <button
      class="btn text-nowrap"
      role="button"
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
      {{ typeof label === 'boolean' ? label.toString() : label }}
    </span>
    <slot name="iconEnd">
      <i :class="`bi bi-${iconClassEnd}`"></i>
    </slot>
  </button>
</template>
