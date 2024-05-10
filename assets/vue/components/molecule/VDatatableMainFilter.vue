<script setup>
import Button from "../atom/Button.vue";

const props = defineProps({
  filter: {type: Object, required: true}
});

const emit = defineEmits(['selectedValue']);
const active = defineModel('activeMainFilter', {type: String, required: true})

const selectMainFilterValue = (value) => {
  emit('selectedValue', value);
};
</script>

<template>
  <div :class="`row row-cols-${filter.values.length}`">
    <div v-for="(data, index) in filter.values" :key="index">
      <Button
          :label="data"
          @click.prevent="selectMainFilterValue(data)"
          :color-class="data === active ? 'primary' : 'outline-secondary'"
          class="w-100"
          size="lg"
      />
    </div>
  </div>
</template>

<style scoped>

</style>