<script setup>
import VDashboardNotification from "../../components/organism/VDashboardNotification.vue";
import {implode, truncate} from "../../composable/formatter/string";

const props = defineProps({
  notifications: {type: Object, required: true}
})

const reservationBaseUrl = '/admin/reservation';
const reviewBaseUrl = '/admin/review';
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
            <span>
        {{ item.user }} wants to book {{ implode(item.lodgings) }} from {{ item.arrivalDate }} to {{
                item.departureDate
              }}
            </span>
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
        <span>
        {{ item.user }} left a review of {{ item.rate }} stars !
        </span>
        <small class="d-block">{{ truncate(item.comment, 30, '...') }}</small>
      </template>
    </VDashboardNotification>
  </section>
</template>

<style scoped>

</style>