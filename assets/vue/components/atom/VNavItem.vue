<script setup>
import Button from "./Button.vue";
import {goTo} from "../../composable/action/redirect";
import {toTitle} from "../../composable/formatter/string";

const props = defineProps({
  label: {type: String, required: true},
  value: {type: String, required: true},
  iconClass: {type: String, default: null},
  colorClass: {type: String, default: 'outline-primary'}
})
</script>

<template>
  <Button :label="toTitle(label)"
          @click.prevent="goTo(value)"
          :icon-class-start="iconClass ? iconClass : null"
          :color-class="colorClass"/>
</template>

<style scoped>

</style>