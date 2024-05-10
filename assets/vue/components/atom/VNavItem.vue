<script setup>
import Button from "./Button.vue";
import {goTo} from "../../composable/action/redirect";
import {toTitle} from "../../composable/formatter/string";

const props = defineProps({
  name: {type: String, required: true},
  value: {type: String, required: true},
  iconClass: {type: String, default: null}
})
</script>

<template>
  <Button :label="toTitle(name)" @click.prevent="goTo(value)" :icon-class-start="iconClass ? iconClass : null"/>
</template>

<style scoped>

</style>