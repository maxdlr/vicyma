<script setup>
import VNavItem from "../components/atom/VNavItem.vue";
import {Collapse} from 'bootstrap';

const props = defineProps({
  navigation: {type: Object, required: true}
})

const isCurrentLocation = (link) => {
  return window.location.pathname === link
}

</script>

<template>
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a href="/" class="navbar-brand fs-4 fw-bold text-info">
        <i class="bi bi-house-fill me-3"></i>
        <span>VICYMA</span>
      </a>
      <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mb-2 mb-lg-0 justify-content-between d-flex align-items-center w-100">
          <li v-for="(link, index) in navigation" :key="index" class="nav-item">
            <div class="py-1">
              <VNavItem
                  :label="link.label"
                  :value="link.value"
                  :icon-class="link.iconClass"
                  color-class="primary"
                  size="lg"
              />
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<style scoped>

</style>