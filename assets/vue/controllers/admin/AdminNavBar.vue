<script setup>
import VNavItem from "../../components/atom/VNavItem.vue";

const props = defineProps({
  navigation: {type: Object, required: true}
})
</script>

<template>
  <ul class="nav nav-pills d-flex flex-column justify-content-start align-items-start p-5 ps-0">
    <li v-for="(link, index) in navigation" :key="index" class="nav-item">
      <div class="py-1">
        <VNavItem
            :name="link.name"
            :value="link.value"
            :icon-class="link.iconClass"
        />
      </div>
    </li>
  </ul>
</template>

<style scoped>

</style>