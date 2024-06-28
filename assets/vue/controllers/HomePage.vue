<script setup>
import HomeHeader from "../components/molecule/HomeHeader.vue";
import {toTitle} from "../composable/formatter/string";
import LodgingDetail from "../components/atom/LodgingDetail.vue";
import AvailableLodgingForm from "../components/molecule/AvailableLodgingForm.vue";
import VDatatable from "../components/organism/VDatatable.vue";
import {computed, onMounted, onUnmounted, ref} from "vue";
import {BREAKPOINTS} from "../constant/bootstrap-constants";

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

const isMdScreen = computed(() => {
  return screenWidth.value < BREAKPOINTS.LG;
})
</script>

<template>
  <HomeHeader :background="headerBackground" title="Résidence Vicyma"/>
  <section class="row px-3">
    <div class="col-12 col-lg-2">
      <AvailableLodgingForm/>
    </div>
    <div class="col-12 col-lg-10">
      <VDatatable
          :data="lodgings"
          :hide-order-by="true"
          :hide-result-count="true"
          :searchable-properties="['description', 'name']"
          reset-button="right"
      >
        <template #customRow="{item}">
          <article
              :class="!isMdScreen ? 'w-75' : 'w-100'"
              class="p-3 border border-info border-1 my-3 rounded-5 row"
          >

            <div class="overflow-hidden col-12 col-lg-5 p-0"
                 style="height: clamp(200px, 80%, 300px)"
            >
              <img
                  :alt="`image representing ${item.name}`"
                  :src="`/build/${item.medias[0].mediaPath}`"
                  class="img-fluid rounded-4 object-fit-cover"
              />
            </div>

            <div class="my-auto col-12 col-lg-7">
              <h3 class="fw-bolder fs-2 text-uppercase fst-italic">
                {{ toTitle(item.name) }}
              </h3>
              <div class="my-4">
                <LodgingDetail :content="`${item.surface} m2`"/>
                <LodgingDetail :content="`${item.roomCount} rooms`" detail="King beds"/>
                <LodgingDetail :content="`Terrace`" :detail="`${item.terraceSurface} m2`"/>
              </div>
              <div class="text-end">
                <span class="text-info">From </span>
                <span class="fw-bold">{{ item.priceByNight }} FCFA</span>
                <span class="text-info"> / night</span>
              </div>
            </div>

          </article>
        </template>
      </VDatatable>
    </div>
  </section>
</template>