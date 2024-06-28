<script setup>
import {
  COLOR_CLASSES,
} from "../../constant/bootstrap-constants";
import {onMounted, onUnmounted, ref} from "vue";
import {toTitle} from "../../composable/formatter/string";
import {getPropertyValue} from "../../composable/formatter/object";
import VButton from "./VButton.vue";
import {SLIDE_DOWN} from "../../constant/animation";
import {vOnClickOutside} from '@vueuse/components'

const props = defineProps({
  label: {type: String, default: "label", required: true},
  options: {type: Array, required: true},
  noEmpty: {type: Boolean, default: false, required: false},
  propertyOf: {type: String, required: false},
  returnRawObject: {type: Boolean, default: false, required: false},

  mainColorClass: {
    type: String,
    default: "info",
    validator(value) {
      return COLOR_CLASSES.includes(value);
    },
  },
});

const selectedOption = defineModel('selectedOption', {required: true});
const emit = defineEmits(['hasSelection'])

const screenWidth = ref(screen.width);
const screenHeight = ref(screen.height);
const isOpen = ref(false);

const handleResize = () => {
  screenWidth.value = screen.width;
  screenHeight.value = screen.height;
};

onMounted(() => {
  window.addEventListener("resize", handleResize);
});
onUnmounted(() => {
  window.removeEventListener("resize", handleResize);
});

const toggleOpen = () => {
  isOpen.value = !isOpen.value
}

const closeList = () => {
  isOpen.value = false
}

const openList = () => {
  isOpen.value = true
}

const select = (value) => {
  selectedOption.value = value;
  toggleOpen();
  emit('hasSelection')
}

</script>

<template>

  <div :id="`select-${label}`"
       v-on-click-outside="closeList"
       class="position-relative"
  >
    <VButton
        :class="isOpen ? 'border-bottom-0 bg-white' : ''"
        :color-class="`outline-${mainColorClass}`"
        :icon-class-end="isOpen ? 'caret-up-fill' : 'caret-down-fill'"
        :label="`${toTitle(label)} • ${selectedOption ? typeof selectedOption === 'object' ? selectedOption.label : selectedOption : 'All'}`"
        :round-class="isOpen ? 'top-5 rounded-bottom-0' : '5'"
        class="w-100 position-relative z-2"
        @click.prevent="openList"
        @keydown.esc="closeList"
    />
    <Transition :name="SLIDE_DOWN">
      <div v-if="isOpen" class="position-absolute top-100 start-0 w-100 z-0">
        <ul :class="`list-group list-group-flush rounded-bottom-5 border border-top-0 border-1 border-${mainColorClass}`">
          <li
              v-if="!noEmpty"
              class="list-group-item list-group-item-action p-0"
              @click.prevent="select('')"
          >
            <VButton :class="`w-100 text-${mainColorClass}`" color-class="" label="All" round-class=""/>
          </li>
          <li
              v-for="(option, index) in options"
              :key="index"
              class="list-group-item list-group-item-action p-0"
          >
            <VButton
                :class="`w-100 text-${mainColorClass}`"
                :label="(propertyOf ? toTitle(getPropertyValue(option, propertyOf)) : option).toString()"
                color-class="" round-class=""
                @click.prevent="select(propertyOf && !returnRawObject ? getPropertyValue(option, propertyOf) : option)"
            />
          </li>
        </ul>
      </div>
    </Transition>
  </div>


  <!--  <div class="form-floating">-->
  <!--    <select-->
  <!--        :id="`select-${label}`"-->
  <!--        v-model="selectedOption"-->
  <!--        :class="`bg-${mainColorClass}`"-->
  <!--        class="form-select form-control"-->
  <!--        @change="emit('hasSelection')"-->
  <!--    >-->
  <!--      <option v-if="!noEmpty" value="">All</option>-->
  <!--      <option v-for="(option, index) in options"-->
  <!--              :key="index" :value="propertyOf && !returnRawObject ? getPropertyValue(option, propertyOf) : option">-->
  <!--        {{ propertyOf ? toTitle(getPropertyValue(option, propertyOf)) : option }}-->
  <!--      </option>-->
  <!--    </select>-->
  <!--    <label :for="`select-${label}`">{{ toTitle(label) }}</label>-->
  <!--  </div>-->
</template>
<style lang="scss" scoped>
@import "../../../styles/animation/slide-down.scss";
</style>