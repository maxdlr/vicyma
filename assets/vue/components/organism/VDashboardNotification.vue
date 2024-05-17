<script setup>
import {goTo} from "../../composable/action/redirect";
import Button from "../atom/Button.vue";
import {ref} from "vue";

const props = defineProps({
  titlePhrase: {type: String, required: true},
  createdOnPhrase: {type: String, required: true},
  baseUrl: {type: String, required: true},
  seeAllUrl: {type: String, required: true},
  items: {type: Object, required: true}
})

const hovering = ref(false)

</script>

<template>
  <article>
    <div class="d-flex justify-content-between align-items-center p-4">
      <div>
        <span class="py-2 px-3 me-3 bg-success fs-3 text-white fw-bold rounded-pill">{{ items.length }}</span>
        <span class="fs-3 fw-bold">{{ titlePhrase }}</span>
      </div>
      <div>
        <Button label="See all" icon-class-end="box-arrow-up-right" @click="goTo(seeAllUrl)"/>
      </div>
    </div>
    <div v-for="item in items" :key="item.id">
      <div class="border border-1 rounded-4 p-4 my-2 d-flex justify-content-between align-items-center border-hover"
      >
        <div>

          <div><span class="badge fs-6 bg-success-subtle text-success">{{ createdOnPhrase }} {{ item.createdOn }}</span>
          </div>

          <div class="ps-3">
            <slot name="notification" :item="item"/>
          </div>
        </div>
        <div>
          <Button label="See" icon-class-end="box-arrow-up-right" @click.prevent="goTo(`${baseUrl}/${item.id}/show`)"/>
        </div>
      </div>
    </div>
  </article>
</template>

<style scoped lang="scss">
@import "bootstrap/scss/bootstrap";

.border-hover {
  &:hover {
    border-color: $primary !important;
  }
}

</style>