<script setup>
import {computed, onBeforeMount} from "vue";
import {useObjectFormatter} from "../../composable/formatter/object";
import Button from "./Button.vue";

const {getPropertyValue} = useObjectFormatter();
const props = defineProps({
  item: {type: Object, required: true},
  exclude: {type: String}
})

const templateClass = {
  property: 'pt-4 pb-2 text-center'
}

onBeforeMount(() => {
  delete props.item[props.exclude]
  delete props.item['id']
})


</script>

<template>
  <div class="row">
    <div v-for="(property, index) in item" :key="index" class="col">
      <div class="position-relative bg-white rounded-3">
        <div class="position-absolute top-0 start-0 ps-2 pt-1 opacity-50"><small>{{ index }}</small></div>

        <div v-if="typeof property !== 'object'" :class="templateClass.property">{{ property }}</div>

        <div v-if="typeof property === 'object'" v-for="item in property" :key="property">
          <div :class="templateClass.property">{{ item }}</div>
        </div>
      </div>

    </div>

  </div>
</template>

<style scoped>

</style>