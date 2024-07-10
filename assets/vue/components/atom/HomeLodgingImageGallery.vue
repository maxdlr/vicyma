<script setup>
const props = defineProps({
  lodging: {type: Object, required: true},
  extraImgCount: {type: [Number, String], default: 3}
})
</script>

<template>
  <div class="row m-0">
    <img
        :alt="`image representing ${lodging.name}`"
        :src="`/build/${lodging.medias[0].mediaPath}`"
        class="img-fluid rounded-4 object-fit-cover col-9 p-0 pe-1"
    />
    <div class="p-0 col-3 rounded-4 overflow-hidden">
      <div v-for="(media, index) in lodging.medias"
           :key="index"
           :class="index === 0 || index === lodging.medias.length - 1  ? '' : 'my-1'"

      >
        <img
            :alt="`image representing ${lodging.name}`"
            :src="`/build/${media.mediaPath}`"
            class="img-fluid object-fit-cover h-100"
        />
      </div>
    </div>
  </div>
</template>
