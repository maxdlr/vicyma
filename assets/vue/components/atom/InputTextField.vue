<script setup>
import { toTitle } from "../../composable/formatter/string";
import InputWrapper from "./InputWrapper.vue";

const props = defineProps({
  label: { type: String, required: true, default: "label" },
  placeholder: { type: String, required: true, default: "placeholder" },
});

const value = defineModel("value", { type: String, default: "" });
</script>

<template>
  <InputWrapper :label="label">
    <input
      type="text"
      :placeholder="toTitle(placeholder)"
      class="border border-0 bg-transparent w-100"
      v-model="value"
    />
  </InputWrapper>
</template>

<style scoped lang="scss">
input {
  &:focus {
    outline: none;
  }
}
</style>
