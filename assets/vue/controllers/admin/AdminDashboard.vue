<script setup>
import VDashboardNotification from "../../components/organism/VDashboardNotification.vue";
import {implode, toTitle, truncate} from "../../composable/formatter/string";

const props = defineProps({
  notifications: {type: Object, required: true}
})

const reservationBaseUrl = '/admin/reservation';
const reviewBaseUrl = '/admin/review';
const userBaseUrl = '/admin/user'
const lodgingBaseUrl = '/admin/lodging'
</script>

<template>
  <section class="row row-cols-2">
    <VDashboardNotification
        :items="notifications.reservations"
        title-phrase="pending reservations"
        created-on-phrase="Requested on"
        :base-url="reservationBaseUrl"
        see-all-url="/admin/business#reservations"
    >
      <template #notification="{item}">
        <a class="fw-bold icon-link" :href="`${userBaseUrl}/${item.user.id}/show`">{{ item.user.value }}</a>
        <span> wants to book </span>
        <div class="d-inline" v-if="item.lodgings.length > 1">
          <div class="d-inline" v-for="(lodging, index) in item.lodgings" :key="index">
            <a class="fw-bold icon-link" :href="`${lodgingBaseUrl}/${lodging.id}/show`"
               v-if="lodging.id !== item.lodgings[item.lodgings.length - 1].id">{{ toTitle(lodging.name) }}</a>
            <div class="d-inline" v-else>
              <span> and </span>
              <a class="fw-bold icon-link" :href="`${lodgingBaseUrl}/${lodging.id}/show`">{{
                  toTitle(lodging.name)
                }}</a>
            </div>
          </div>
        </div>
        <div v-else class="d-inline">
          <div class="d-inline" v-for="(lodging, index) in item.lodgings" :key="index">
            <a class="fw-bold icon-link" :href="`${lodgingBaseUrl}/${lodging.id}/show`">{{ toTitle(lodging.name) }}</a>
          </div>
        </div>
        <span> from </span>
        <span class="fw-bold">{{ item.arrivalDate }}</span>
        <span> to </span>
        <span class="fw-bold">{{ item.departureDate }}.</span>
      </template>
    </VDashboardNotification>

    <VDashboardNotification
        :items="notifications.reviews"
        title-phrase="pending reviews"
        created-on-phrase="Left on"
        :base-url="reviewBaseUrl"
        see-all-url="/admin/business#reviews"
    >
      <template #notification="{item}">
        <a class="icon-link fw-bold" :href="`${userBaseUrl}/${item.user.id}/show`">{{ item.user.value }}</a>
        <span> left a review</span>
        <span> of {{ item.rate }} stars !</span>
        <small class="d-block">{{ truncate(item.comment, 30, '...') }}</small>
      </template>
    </VDashboardNotification>
  </section>
</template>

<style scoped>

</style>