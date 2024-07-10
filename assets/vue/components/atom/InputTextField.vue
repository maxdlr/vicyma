<script setup>
import {toTitle} from "../../composable/formatter/string";

const props = defineProps({
  label: {type: String, required: true, default: 'label'},
  placeholder: {type: String, required: true, default: 'placeholder'},
})
</script>

<template>
  <div class="rounded-pill bg-warning">
    <div class="p-3 ps-4 pe-0">
      <span class="fw-bold fst-italic">{{toTitle(label)}}</span>
      <input
          type="text"
          :placeholder="toTitle(placeholder)"
          class="border border-0 bg-transparent w-100"
      >
    </div>
  </div>
</template>

<style scoped lang="scss">
input {
  &:focus {
    outline: none;
  }
}

</style>