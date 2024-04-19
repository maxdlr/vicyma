<script setup>
defineProps({
  label: {type: String, default: null},
  colorClass: {type: String, default: "primary"},
  size: {type: String, default: null},
  roundClass: {type: String, default: null},
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
    <slot name="iconStart"/>
    <span v-if="label" :class="[
        $slots['iconStart'] ? 'ps-2' : '',
        $slots['iconEnd'] ? 'pe-2' : ''
          ]">
      {{ label }}
    </span>
    <slot name="iconEnd"/>
  </button>
</template>
