<script setup>
import Button from "../atom/Button.vue";
import {computed} from "vue";

const props = defineProps({
  items: {type: Array, required: true},
})

const resultCount = computed(() => {
  return props.items.length
});
const emit = defineEmits(['reset'])

</script>

<template>
  <div class="p-3">
    <div v-if="resultCount > 0">{{ resultCount }} result{{ resultCount > 1 ? 's' : '' }} found!</div>
    <div v-if="resultCount === 0">
      <span>No results found!</span>
      <Button label="reset" size="sm" @click.prevent="emit('reset')" class="mx-1"/>
    </div>
  </div>

  <div v-for="(item) in items" :key="item.id">
    <div
        class="row my-2 justify-content-center align-items-start border border-2 rounded-4 border-primary my-1 px-2 py-3">
      <slot name="row" :item="item"/>
      <div class="d-flex flex-column justify-content-center col-2">
        <slot name="buttons" :item="item"/>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>