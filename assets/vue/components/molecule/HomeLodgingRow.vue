<script setup>
import { toTitle } from "../../composable/formatter/string";
import HomeLodgingImageGallery from "../../components/atom/HomeLodgingImageGallery.vue";
import HomeLodgingDetail from "../../components/atom/HomeLodgingDetail.vue";
import { truncate } from "../../composable/formatter/string";
import { computed, onMounted, onUnmounted, ref } from "vue";
import { BREAKPOINTS } from "../../constant/bootstrap-constants";

const props = defineProps({
  lodging: { type: Object, required: true },
});

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

const isLgScreen = computed(() => {
  return screenWidth.value < BREAKPOINTS.LG;
});

const hovering = ref(false);
const clicked = ref(false);
</script>

<template>
  <article
    :class="[
      !isLgScreen ? 'w-75' : 'w-100',
      hovering
        ? clicked
          ? 'bg-primary-subtle'
          : 'border-info bg-info-subtle cursor-pointer'
        : '',
    ]"
    class="p-3 border border-secondary border-1 my-3 rounded-5 row"
    @mouseenter="hovering = true"
    @mouseleave="hovering = false"
    @mousedown="clicked = true"
    @mouseup="clicked = false"
  >
    <div class="overflow-hidden col-12 col-md-5 p-0 align-self-center">
      <HomeLodgingImageGallery :lodging="lodging" />
    </div>

    <div class="my-auto col-12 col-md-7">
      <h3 class="fw-bolder fs-2 text-uppercase fst-italic text-info-emphasis">
        {{ toTitle(lodging.name) }}
      </h3>
      <div class="my-3">
        <HomeLodgingDetail :content="`${lodging.surface} m2`" />
        <HomeLodgingDetail
          :content="`${lodging.roomCount} rooms`"
          detail="King beds"
        />
        <HomeLodgingDetail
          :content="`Terrace`"
          :detail="`${lodging.terraceSurface} m2`"
        />
      </div>
      <div class="my-3">
        {{ truncate(lodging.description, 200, "...") }}
      </div>
      <div class="text-end">
        <span class="text-info">From </span>
        <span class="fw-bold">{{ lodging.priceByNight }} FCFA</span>
        <span class="text-info"> / night</span>
      </div>
    </div>
  </article>
</template>
