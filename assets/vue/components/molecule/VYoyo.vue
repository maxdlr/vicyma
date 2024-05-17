<script setup>
import {COLOR_CLASSES} from "../../constant/bootstrap-constants";
import Button from "../atom/Button.vue";
import {onUpdated, ref} from "vue";
import {SLIDE_DOWN, SLIDE_UP} from "../../constant/animation";

const props = defineProps({
  label: {type: String, default: "Action", required: false},
  iconClass: {type: String, default: null, required: false},
  bgColorClass: {
    type: String, default: "", required: false,
    validator(value) {
      return COLOR_CLASSES.includes(value)
    }
  },
  textColorClass: {
    type: String, default: "dark", required: false,
    validator(value) {
      return COLOR_CLASSES.includes(value)
    }
  },
  direction: {
    type: String, default: 'down-center', required: false, validator(value) {
      return ['up-right', 'up-left', 'up-center', 'down-right', 'down-left', 'down-center'].includes(value)
    }
  },
  column: {type: Boolean, default: true, required: false},
})

const isOpen = defineModel('isOpen', {type: Boolean, default: false, required: false})

const openYoyo = () => {
  isOpen.value = true;
}
const closeYoyo = () => {
  isOpen.value = false;
}

const toggleOpen = () => {
  isOpen.value ? closeYoyo() : openYoyo();
}

const buttons = ref(null)
const styleButtonList = (yoyoBtns) => {
  const buttonsEl = yoyoBtns.children;
  for (const btn of buttonsEl) {
    btn.classList.add('my-1')
  }
}

onUpdated(() => {
  if (buttons.value) {
    styleButtonList(buttons.value)
  }
})
</script>

<template>
  <div class="position-relative d-inline py-2">
    <!--    <Button-->
    <!--        :label="label"-->
    <!--        @mouseover="openYoyo"-->
    <!--        @click.prevent="toggleOpen"-->
    <!--        :icon-class-end="isOpen ? 'caret-up-fill' : 'caret-down-fill'"-->
    <!--        class="d-inline"-->
    <!--    />-->
    <Transition :name="direction.includes('up') ? SLIDE_UP : SLIDE_DOWN">
      <div
          v-if="isOpen"
          class="d-flex position-absolute p-2 pt-0 z-1030"
          @mouseover="openYoyo"
          :class="[
              column ? 'flex-column' : '',
              column && direction.includes('left') ? 'align-items-end' : 'align-items-start',
              column && direction.includes('right') ? 'justify-content-start' : 'justify-content-end',
              !column && direction.includes('left') ? 'justify-content-end' : 'justify-content-start',
              direction.includes('up') ? 'bottom-100' : 'top-0',
              direction.includes('left') ? 'end-100' : '',
              direction.includes('right') ? 'start-100' : '',
          ]"
          ref="buttons"
      >
        <slot name="buttons" :is="Button"/>
      </div>
    </Transition>
  </div>
</template>

<style scoped lang="scss">
@import "../../../../assets/styles/animation/slide-down.scss";
@import "../../../../assets/styles/animation/slide-up.scss";

.z-1030 {
  z-index: 1030;
}
</style>