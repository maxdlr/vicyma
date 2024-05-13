<script setup>
import {onBeforeMount, onUnmounted, ref} from "vue";
import AdminReservationRequests from "datatables/AdminReservationRequests.vue";
import AdminUsers from "datatables/AdminUsers.vue";
import AdminMessages from "datatables/AdminMessages.vue";
import AdminReviews from "datatables/AdminReviews.vue";
import {toTitle} from "../../composable/formatter/string";

const props = defineProps({
  datatables: {type: Object, required: true}
})

onBeforeMount(() => {
  const currentAnchor = window.location.hash;
  currentTab.value = currentAnchor ? currentAnchor.substring(1) : 'reservations';
})

const changeTab = (newTab) => {
  currentTab.value = newTab;
  window.location.hash = '#' + newTab
}

const currentTab = ref('reservations');

const tabs = {
  reservations: AdminReservationRequests,
  users: AdminUsers,
  messages: AdminMessages,
  reviews: AdminReviews
}

onUnmounted(() => {
  localStorage.clear()
})

</script>

<template>
  <nav>
    <div class="nav nav-tabs" role="tablist">
      <button
          v-for="(datatable, index) in datatables"
          :key="index"
          :class="['nav-link', {active: currentTab === index}]"
          @click="changeTab(index)"
          data-bs-toggle="tab"
          type="button"
          role="tab"
          :aria-selected="currentTab === index"
      >
        {{ toTitle(datatable.name) }}
      </button>
    </div>
  </nav>
  <KeepAlive>
    <component
        :title="datatables[currentTab].name"
        :is="tabs[currentTab]"
        :data="datatables[currentTab].data"
    ></component>
  </KeepAlive>
</template>

<style scoped>

</style>