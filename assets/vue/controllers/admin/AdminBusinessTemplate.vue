<script setup>
import {ref} from "vue";
import AdminReservationRequests from "./datatables/AdminReservationRequests.vue";
import AdminUsers from "./datatables/AdminUsers.vue";

const props = defineProps({
  datatables: {type: Object, required: true}
})

const currentTab = ref('reservations')

const tabs = {
  reservations: AdminReservationRequests,
  users: AdminUsers,
}

</script>

<template>
  <nav>
    <div class="nav nav-tabs" role="tablist">
      <button
          v-for="(datatable, index) in datatables"
          :key="index"
          :class="[
              'nav-link',
              {
                active: currentTab === index,
              }
            ]"
          @click="currentTab = index"
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
        :is="tabs[currentTab]"
        class="tab"
        :settings="datatables[currentTab].settings"
        :items="datatables[currentTab].items"
    ></component>
  </KeepAlive>
</template>

<style scoped>

</style>