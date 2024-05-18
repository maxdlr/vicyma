<script setup>
import Button from "../atom/Button.vue";
import {toTitle} from "../../composable/formatter/string";

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
  <div class="border border-primary border-1 position-relative px-5 pt-5 pb-3 rounded-pill">
    <span class="position-absolute top-0 start-0 ms-5 mt-3 badge badge bg-success rounded-pill">{{
        toTitle(filter.name)
      }}</span>
    <div :class="`row row-cols-${filter.values.length + 1}`">
      <div class="px-1">
        <Button
            label="All"
            @click.prevent="selectMainFilterValue('')"
            :color-class="'' === active ? 'primary' : 'outline-secondary'"
            class="w-100"
            size="lg"
        />
      </div>
      <div v-for="(data, index) in filter.values" :key="index" class="px-1">
        <Button
            :label="data"
            @click.prevent="selectMainFilterValue(data.toString())"
            :color-class="data.toString() === active ? 'primary' : 'outline-secondary'"
            class="w-100"
            size="lg"
        />
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>