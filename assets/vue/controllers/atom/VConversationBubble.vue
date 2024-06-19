<script setup>
const props = defineProps({
  userId: {type: Number, required: true},
  username: {type: String, required: true},
  datetime: {type: Object, required: true},
  content: {type: String, required: true},
  isAdmin: {type: Boolean, required: true}
})

const urlRegex = RegExp(`(http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])`)

console.log(props.content.toLowerCase().match(urlRegex))

</script>

<template>

  <div :class="[isAdmin ? 'text-end' : 'text-start']">
    <div class="px-5 pb-3 pt-5 mt-2 rounded-5 text-white d-inline-block w-75 position-relative"
         :class="[isAdmin ? 'bg-secondary' : 'bg-primary']">
      <span class="position-absolute top-0 start-0 pt-3 ps-4 fw-bold badge fs-6">{{ username }}, le {{ datetime.date }} à {{
          datetime.time
        }}</span>

      <i class="bi bi-triangle-fill position-absolute bottom-0"
         :class="[isAdmin ? 'start-100 text-secondary' : 'end-100 text-primary']"
      ></i>

      <span class="text-start">{{ content }}</span>

    </div>
  </div>
</template>

<style scoped>

</style>