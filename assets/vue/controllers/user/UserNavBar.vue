<script setup>
import VNavItem from "../../components/atom/VNavItem.vue";

const props = defineProps({
  navigation: {type: Object, required: false}
})

const isCurrentLocation = (link) => {
  return window.location.pathname === link
}
</script>

<template>
  <ul class="nav nav-pills d-flex flex-column justify-content-start align-items-start p-5 ps-0">

    <li v-for="(link, index) in navigation" :key="index" class="nav-item">
      <div class="py-1">
        <VNavItem
            :label="link.label"
            :value="link.value"
            :icon-class="link.iconClass"
            :color-class="isCurrentLocation(link.value) ? 'primary' : 'outline-primary'"
        />
      </div>
    </li>
  </ul>
</template>

<style scoped>

</style>