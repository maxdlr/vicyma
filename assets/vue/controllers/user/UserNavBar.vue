<script setup>

import VNavItem from "../../components/atom/VNavItem.vue";
import Button from "../../components/atom/Button.vue";
</script>

<template>
  <ul class="nav nav-pills d-flex flex-column justify-content-start align-items-start p-5 ps-0">
    <li class="nav-item">
      <Button label="Address" icon-class-start="house" data-bs-toggle="modal" data-bs-target="#userAccountAddress"/>
    </li>
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