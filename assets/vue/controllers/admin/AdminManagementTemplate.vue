<script setup>
import {onBeforeMount, onUnmounted, ref} from "vue";
import {toTitle} from "../../composable/formatter/string";
import AdminLodgings from "./datatables/AdminLodgings.vue";
import AdminBeds from "./datatables/AdminBeds.vue";

const props = defineProps({
  datatables: {type: Object, required: true}
})

onBeforeMount(() => {
  const currentAnchor = window.location.hash;
  currentTab.value = currentAnchor ? currentAnchor.substring(1) : 'lodgings';
})

const changeTab = (newTab) => {
  currentTab.value = newTab;
  window.location.hash = '#' + newTab
}

const currentTab = ref('lodgings');

const tabs = {
  lodgings: AdminLodgings,
  beds: AdminBeds
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