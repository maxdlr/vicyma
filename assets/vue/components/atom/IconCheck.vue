<script setup>
import {
  ROTATE,
  SLIDE_DOWN,
  SLIDE_LEFT,
  SLIDE_RIGHT,
  SLIDE_UP,
} from '../../constant/animation';

const props = defineProps({
  colorClass: {type: String, default: 'success'},
  animation: {
    type: String,
    default: 'rotate',
    validator(value, props) {
      return [ROTATE, SLIDE_UP, SLIDE_DOWN, SLIDE_RIGHT, SLIDE_LEFT].includes(
          value,
      );
    },
  },
});
</script>

<template>
  <Transition :name="animation">
    <div
        class="badge"
        :class="`text-${colorClass}`"
    >
      <svg
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          fill="currentColor"
          class="bi bi-check-circle-fill"
          viewBox="0 0 16 16"
      >
        <path
            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"
        />
      </svg>
    </div>
  </Transition>
</template>

<style scoped>
.slide-right-enter-active,
.slide-left-enter-active,
.slide-up-enter-active,
.slide-down-enter-active,
.rotate-enter-active {
  transition: transform 0.7s cubic-bezier(0.1, 1, 0.2, 1);
}

.slide-right-enter-from {
  transform: translateX(-30px);
}

.slide-left-enter-from {
  transform: translateX(30px);
}

.slide-up-enter-from {
  transform: translateY(30px);
}

.slide-down-enter-from {
  transform: translateY(-30px);
}

.rotate-enter-from {
  transform: rotate(-180deg);
}

.slide-right-leave-to,
.slide-up-leave-to,
.slide-left-leave-to,
.slide-down-leave-to,
.rotate-leave-to {
  opacity: 0;
}
</style>
