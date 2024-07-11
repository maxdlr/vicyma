<script setup>
import {toTitle} from "../../composable/formatter/string";
const props = defineProps({
  label: {type: String, required: true, default: 'label'},
})
</script>

<template>
  <div class="rounded-pill bg-transparent border border-1 border-info">
    <div class="ps-4 pe-2">
      <slot/>
    </div>
  </div>
</template>

<style scoped>

</style>