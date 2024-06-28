<script setup>
import HomeHeader from "../components/molecule/HomeHeader.vue";
import {toTitle} from "../composable/formatter/string";
import LodgingDetail from "../components/atom/LodgingDetail.vue";
import AvailableLodgingForm from "../components/molecule/AvailableLodgingForm.vue";
import VDatatable from "../components/organism/VDatatable.vue";

const props = defineProps({
  headerBackground: {type: String, required: true},
  lodgings: {type: Object, required: true},
})
</script>

<template>
  <HomeHeader :background="headerBackground" title="Résidence Vicyma"/>
  <section class="row">
    <div class="col-2">
      <AvailableLodgingForm/>
    </div>
    <div class="col-10">
      <VDatatable
          :data="lodgings"
          :hide-order-by="true"
          :hide-result-count="true"
          :searchable-properties="['description', 'name']"
          reset-button="right"
      >
        <template #customRow="{item}">
          <article
              class="p-3 border border-info border-1 my-3 rounded-5 row"
              style="max-width: 75% !important; aspect-ratio: 1/0.5 !important;"
          >

            <div class="overflow-hidden col-5 ps-0">
              <img
                  :alt="`image representing ${item.name}`"
                  :src="`/build/${item.medias[0].mediaPath}`"
                  class="img-fluid rounded-4 object-fit-cover"
                  style="width: 100% !important; aspect-ratio: 0.8/1"
              />
            </div>

            <div class="my-auto col-7">
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