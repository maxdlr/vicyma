<script setup>
import HomeHeader from "../components/molecule/HomeHeader.vue";
import AvailableLodgingForm from "../components/molecule/AvailableLodgingForm.vue";
import VDatatable from "../components/organism/VDatatable.vue";
import {onMounted, onUnmounted, ref} from "vue";
import HomeLodgingRow from "../components/molecule/HomeLodgingRow.vue";

const props = defineProps({
  headerBackground: {type: String, required: true},
  lodgings: {type: Object, required: true},
})
const screenWidth = ref(window.innerWidth);
const screenHeight = ref(window.innerHeight);
const handleResize = () => {
  screenWidth.value = window.innerWidth;
  screenHeight.value = window.innerHeight;
};
onMounted(() => {
  window.addEventListener("resize", handleResize);
});
onUnmounted(() => {
  window.removeEventListener("resize", handleResize);
});

</script>

<template>
  <HomeHeader :background="headerBackground" title="Résidence Vicyma"/>
  <section class="row px-3">
    <div class="col-12 col-lg-3">
      <AvailableLodgingForm/>
    </div>
    <div class="col-12 col-lg-9">
      <VDatatable
          :data="lodgings"
          :hide-order-by="true"
          :hide-result-count="true"
          reset-button="right"
      >
        <template #customRow="{item}">
          <HomeLodgingRow :lodging="item"/>
        </template>
      </VDatatable>
    </div>
  </section>
</template>