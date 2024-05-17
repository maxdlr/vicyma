<script setup>
import {isEmpty} from "../../composable/formatter/object";
import {singularize, toTitle, truncate} from "../../composable/formatter/string"
import {getAverage} from "../../composable/formatter/number";

const props = defineProps({
  name: {type: String},
  value: {type: [String, Object, Array, Number, Boolean]}
})

const templateClass = {
  property: 'py-1 text-center',
}
</script>

<template>
  <div class="position-relative bg-white rounded-3 pt-4">
    <div class="position-absolute top-0 start-0 ps-2 pt-1 opacity-50"><small>{{ name }}</small></div>

    <!-- boolean ----------------------------------------------------------------------------------------------------------- -->

    <div v-if="typeof value === 'boolean'" class="text-center">
      <span class="badge bg-secondary ">{{ value.toString() }}</span>
    </div>

    <!-- string ----------------------------------------------------------------------------------------------------------- -->

    <div v-if="typeof value === 'string'" :class="templateClass.property">
      <span v-if="name !== 'email'">
      {{ value.length > 30 ? toTitle(truncate(value, 30, '...')) : toTitle(value) }}
        </span>
      <span v-else>{{ value }}</span>
    </div>

    <!-- number ----------------------------------------------------------------------------------------------------------- -->

    <div v-if="typeof value === 'number'" :class="templateClass.property">
      {{ value }}
    </div>

    <!-- collection ----------------------------------------------------------------------------------------------------------- -->

    <div v-if="typeof value === 'object'">

      <div v-if="name === 'reviews'" class="text-center">
        <span>{{ getAverage(value) }}{{ getAverage(value) ? '/5' : '' }}</span>
      </div>

      <div v-else-if="!value">
      </div>

      <div v-else-if="typeof value[Object.keys(value)[1]] === 'string'">
        <div :class="templateClass.property" v-if="typeof value[Object.keys(value)[1]] === 'string'">
          <a :href="`/admin/${singularize(name)}/${value[Object.keys(value)[0]]}/show`"
             class="icon-link icon-link-hover text-primary">
            {{ toTitle(value[Object.keys(value)[1]]) }}
            <i class="bi bi-arrow-right-short fs-3"></i>
          </a>
        </div>
      </div>

      <div v-else v-for="item in value" :key="value">
        <div :class="templateClass.property" v-if="typeof item[Object.keys(item)[1]] === 'string'">
          <a :href="`/admin/${singularize(name)}/${item[Object.keys(item)[0]]}/show`"
             class="icon-link icon-link-hover text-primary">
            {{ toTitle(item[Object.keys(item)[1]]) }}
            <i class="bi bi-arrow-right-short fs-3"></i>
          </a>
        </div>
      </div>

    </div>

    <!-- empty ----------------------------------------------------------------------------------------------------------- -->

    <div v-if="(isEmpty(value) || value.length === 0) && typeof value !== 'number' && typeof value !== 'boolean'"
         :class="templateClass.property">
      <span class="fst-italic opacity-50">--- empty ---</span>
    </div>

  </div>
</template>

<style scoped>

.icon-link > .bi {
  height: unset !important;
}

</style>