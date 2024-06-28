<script setup>

const props = defineProps({
  content: {type: String, required: true},
  detail: {type: String, default: null},
})
</script>

<template>
  <div class="fs-4">
    <div class="d-inline text-primary fst-italic fw-bold">
      <i class="bi bi-arrow-right-short text-primary"/>
      <span>{{ content }}</span>
    </div>
    <div v-if="detail" class="d-inline text-info">
      <i class="bi bi-caret-right-fill mx-2"></i>
      <span>{{ detail }}</span>
    </div>
  </div>
</template>