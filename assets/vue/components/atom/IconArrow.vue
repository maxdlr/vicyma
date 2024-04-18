<script setup>
import {ROTATE} from "../../constant/animation";

defineProps({
  direction: {
    type: String,
    default: null,
    required: true,
    validator(value) {
      return ["right", "left", "up", "down"].includes(value);
    },
  },
  colorClass: {type: String, default: "primary", required: false},
});
</script>

<template>
  <div :class="`text-${colorClass}`">
    <svg
        xmlns="http://www.w3.org/2000/svg"
        width="16"
        height="16"
        fill="currentColor"
        :class="{
        'bi bi-caret-right-fill': direction === 'right',
        'bi bi-caret-left-fill': direction === 'left',
        'bi bi-caret-up-fill': direction === 'up',
        'bi bi-caret-down-fill': direction === 'down',
      }"
        viewBox="0 0 16 16"
    >
      <TransitionGroup :name="ROTATE">
        <path
            v-if="direction === 'right'"
            d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"
        ></path>

        <path
            v-if="direction === 'left'"
            d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"
        ></path>

        <path
            v-if="direction === 'up'"
            d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"
        ></path>

        <path
            v-if="direction === 'down'"
            d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"
        ></path>
      </TransitionGroup>
    </svg>
  </div>
</template>

<style scoped>
.rotate-enter-active {
  transition: transform 0.7s cubic-bezier(0.1, 1, 0.2, 1);
  transform-origin: center;
}

.rotate-enter-from {
  transform: rotate(-180deg);
}

.rotate-leave-to {
  opacity: 0;
}
</style>
