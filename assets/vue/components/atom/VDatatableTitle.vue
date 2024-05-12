<script setup>
import {toTitle} from "../../composable/formatter/string";

const props = defineProps({
  title: {type: String, required: true},
  description: {type: String}
})
</script>

<template>
  <div>
    <h1 class="fs-3 text-primary">{{ toTitle(title) }}</h1>
    <p v-if="description">{{ description }}</p>
  </div>
</template>

<style scoped>

</style>