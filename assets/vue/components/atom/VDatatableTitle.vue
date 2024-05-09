<script setup>
import {useStringFormatter} from "../../composable/formatter/string";

const {toTitle} = useStringFormatter();

const props = defineProps({
  title: {type: String, required: true},
  description: {type: String}
})
</script>

<template>
  <div>
    <h1>{{ toTitle(title) }}</h1>
    <p v-if="description">{{ description }}</p>
  </div>
</template>

<style scoped>

</style>