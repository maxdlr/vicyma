<script setup>
import {computed, onBeforeMount, ref} from "vue";
import AdminReservationRequests from "./datatables/AdminReservationRequests.vue";
import AdminUsers from "./datatables/AdminUsers.vue";
import AdminMessages from "./datatables/AdminMessages.vue";
import AdminReviews from "./datatables/AdminReviews.vue";

const props = defineProps({
  datatables: {type: Object, required: true}
})

onBeforeMount(() => {
  const currentAnchor = window.location.hash;

  if (currentAnchor) {
    currentTab.value = currentAnchor.substring(1)
  } else {
    currentTab.value = 'reservations'
  }
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

</script>

<template>
  <nav>
    <div class="nav nav-tabs" role="tablist">
      <button
          v-for="(datatable, index) in datatables"
          :key="index"
          :class="['nav-link',{active: currentTab === index}]"
          @click="changeTab(index)"
          data-bs-toggle="tab"
          type="button"
          role="tab"
          :aria-selected="currentTab === index"
      >
        {{ datatable.name }}
      </button>
    </div>
  </nav>
  <KeepAlive>
    <component
        :title="datatables[currentTab].name"
        :is="tabs[currentTab]"
        class="tab"
        :data="datatables[currentTab].data"
    ></component>
  </KeepAlive>
</template>

<style scoped>

</style>