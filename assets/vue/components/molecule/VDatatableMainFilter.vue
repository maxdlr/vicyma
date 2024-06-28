<script setup>
import Button from "../atom/VButton.vue";
import {toTitle} from "../../composable/formatter/string";
import {BREAKPOINTS} from "../../constant/bootstrap-constants";
import {ref} from "vue";

const props = defineProps({
  filter: {type: Object, required: true},
  screenWidth: {type: [Number, String]},
  screenHeight: {type: [Number, String]},
});

const emit = defineEmits(['selectedValue']);
const active = defineModel('activeMainFilter', {type: String, required: true})

const selectMainFilterValue = (value) => {
  emit('selectedValue', value);
};

const isMdScreen = ref(props.screenWidth < BREAKPOINTS.MD)
</script>

<template>
  <div class="border border-primary border-1 position-relative px-5 pt-5 pb-3 rounded-pill">
    <span class="position-absolute top-0 start-0 ms-5 mt-3 badge badge bg-success rounded-pill">{{
        toTitle(filter.name)
      }}</span>
    <div
        :class="!isMdScreen ? `row row-cols-${filter.values.length + 1}` : 'horizontal-scroll-container'"
    >
      <div :class="isMdScreen ? 'horizontal-scroll-item' : ''" class="px-1">
        <Button
            :color-class="'' === active ? 'primary' : 'outline-secondary'"
            class="w-100"
            label="All"
            size="lg"
            @click.prevent="selectMainFilterValue('')"
        />
      </div>
      <div v-for="(data, index) in filter.values"
           :key="index"
           :class="isMdScreen ? 'horizontal-scroll-item' : ''"
           class="px-1"
      >
        <Button
            :color-class="data.toString() === active ? 'primary' : 'outline-secondary'"
            :label="data"
            class="w-100"
            size="lg"
            @click.prevent="selectMainFilterValue(data.toString())"
        />
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
@import "../../../styles/horizontal-scroll";

</style>