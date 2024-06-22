<script setup>
import {onBeforeMount, onUnmounted, ref, shallowRef} from "vue";
import {toTitle} from "../../composable/formatter/string";
import {components} from "@symfony/ux-vue/dist/components";

const props = defineProps({
  datatables: {type: Object, required: true},
  components: {type: components, required: true},
  defaultTab: {type: String, required: true},
  hiddenEmptyDatatables: {type: Array, required: false, default: []}
})

onBeforeMount(() => {
  tabs.value = props.components;
  currentTab.value = props.defaultTab;
  const currentAnchor = window.location.hash;
  currentTab.value = currentAnchor ? currentAnchor.substring(1) : props.defaultTab;
})

const changeTab = (newTab) => {
  currentTab.value = newTab;
  window.location.hash = '#' + newTab
}

const currentTab = ref();
const tabs = shallowRef();

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
        :hide-empty="hiddenEmptyDatatables.includes(datatables[currentTab].name)"
    ></component>
  </KeepAlive>
</template>