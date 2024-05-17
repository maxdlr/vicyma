<script setup>
import {isEmpty} from "../../composable/formatter/object";
import {singularize, toTitle, truncate} from "../../composable/formatter/string"
import {getReviewAverage} from "../../composable/formatter/number";
import {rand} from "@vueuse/core";
import {Collapse} from 'bootstrap';
import Button from "./Button.vue";
import {ref} from "vue";

const props = defineProps({
  name: {type: String},
  value: {type: [String, Object, Array, Number, Boolean]}
})

const templateClass = {
  property: 'py-1 text-center',
  propertyListItem: 'text-center'
}

const randomSlug = 'slug' + rand(0, 10000000);

const isCollapseOpen = ref(false)

const valueDisplayClass = 'fs-5 fw-bold'

</script>

<template>
  <div class="position-relative bg-white rounded-3 pt-4 h-100">
    <div class="position-absolute top-0 start-0 ps-2 pt-1 opacity-50"><small>{{ name }}</small></div>

    <!-- empty ----------------------------------------------------------------------------------------------------------- -->

    <div
        v-if="(isEmpty(value) || value.length === 0) && typeof value !== 'number' && typeof value !== 'boolean'"
        :class="templateClass.property">
      <span class="fst-italic opacity-50">--- empty ---</span>
    </div>


    <!-- boolean ----------------------------------------------------------------------------------------------------------- -->

    <div v-else-if="typeof value === 'boolean'" class="text-center">
      <span class="badge fs-6 rounded-pill" :class="value ? 'text-bg-success' : 'text-bg-secondary'">{{
          value.toString()
        }}</span>
    </div>

    <!-- string ----------------------------------------------------------------------------------------------------------- -->

    <div v-else-if="typeof value === 'string'" :class="templateClass.property">
      <span v-if="name !== 'email'" :class="valueDisplayClass">
      {{ value.length > 30 ? toTitle(truncate(value, 30, '...')) : toTitle(value) }}
        </span>
      <span v-else>{{ value }}</span>
    </div>

    <!-- number ----------------------------------------------------------------------------------------------------------- -->

    <div v-else-if="typeof value === 'number'" :class="templateClass.property">
      <span :class="valueDisplayClass">{{ value }}</span>
    </div>

    <!-- collection ----------------------------------------------------------------------------------------------------------- -->
    <div v-else-if="typeof value === 'object'">

      <div v-if="name === 'reviews'" class="text-center">
        <span :class="valueDisplayClass">{{ getReviewAverage(value) }}{{ getReviewAverage(value) ? '/5' : '' }}</span>
      </div>

      <div v-else-if="typeof value[Object.keys(value)[1]] === 'string'">
        <div :class="templateClass.property" v-if="typeof value[Object.keys(value)[1]] === 'string'">
          <a :href="`/admin/${singularize(name)}/${value[Object.keys(value)[0]]}/show`"
             class="icon-link icon-link-hover text-primary" :class="valueDisplayClass">
            {{ toTitle(value[Object.keys(value)[1]]) }}
            <i class="bi bi-arrow-right-short fs-3"></i>
          </a>
        </div>
      </div>

      <div v-else-if="value.length < 2">
        <div :class="templateClass.propertyListItem"
             v-if="typeof value[0][Object.keys(value[0])[1]] === 'string' || typeof value[0][Object.keys(value[0])[1]] === 'number'">
          <a :href="`/admin/${singularize(name)}/${value[0][Object.keys(value[0])[0]]}/show`"
             class="icon-link icon-link-hover text-primary" :class="valueDisplayClass">
            {{ toTitle(value[0][Object.keys(value[0])[1]]) }}
            <i class="bi bi-arrow-right-short fs-3"></i>
          </a>
        </div>
      </div>

      <div v-else>

        <Button
            :icon-class-end="isCollapseOpen ? 'caret-up-fill' : 'caret-down-fill'"
            data-bs-toggle="collapse"
            :data-bs-target="`#collection-collapse-${randomSlug}`"
            :aria-expanded="false"
            :aria-controls="`collection-collapse-${randomSlug}`"
            class="w-100 text-primary pb-0"
            color-class=""
            @click="isCollapseOpen = !isCollapseOpen"
            size="lg"
        />

        <div class="collapse" :id="`collection-collapse-${randomSlug}`">
          <div v-for="item in value" :key="value">
            <div :class="templateClass.propertyListItem"
                 v-if="typeof item[Object.keys(item)[1]] === 'string' || typeof value[0][Object.keys(value[0])[1]] === 'number'">
              <a :href="`/admin/${singularize(name)}/${item[Object.keys(item)[0]]}/show`"
                 class="icon-link icon-link-hover text-primary" :class="valueDisplayClass">
                <span>{{ toTitle(item[Object.keys(item)[1]]) }}</span>
                <span v-if="item[Object.keys(item)[2]]">
                <span> - </span>
                <span>{{ toTitle(item[Object.keys(item)[2]]) }}</span>
                  </span>
                <i class="bi bi-arrow-right-short fs-3"></i>
              </a>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
</template>

<style scoped>

.icon-link > .bi {
  height: unset !important;
}

</style>