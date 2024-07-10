<script setup>
import {onBeforeMount, ref} from "vue";
import {toTitle} from "../../composable/formatter/string";

const props = defineProps({
  background: {type: Object, required:true},
  title: {type: String, default: "title", required: true}
})

const backgroundHeaderPath = ref();

onBeforeMount(() => {
  backgroundHeaderPath.value = window.location.origin + '/build/' + props.background.mediaPath;
})

</script>

<template>
  <header class="position-relative d-flex justify-content-center align-items-center rounded-4"
          :style="`background-image: url(${backgroundHeaderPath});`"
  >
    <h1 class="fs-1 text-light fw-bolder">{{toTitle(title)}}</h1>
  </header>

</template>

<style scoped lang="scss">

header {
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  width: 100% !important;
  height: 500px !important;
}

</style>