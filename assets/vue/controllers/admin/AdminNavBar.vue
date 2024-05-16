<script setup>
import VNavItem from "../../components/atom/VNavItem.vue";
import {computed, ref} from "vue";

const props = defineProps({
  navigation: {type: Object, required: true}
})

const isThisHere = (link) => {
  return window.location.href.includes(link)
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
            :color-class="isThisHere(link.value) ? 'primary' : 'outline-primary'"
        />
      </div>
    </li>
  </ul>
</template>

<style scoped>

</style>