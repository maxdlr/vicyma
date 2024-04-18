<script setup>
import {onBeforeMount, ref} from "vue";

const props = defineProps({
  item: {type: Object, required: true},
  exclude: {type: String}
})

const templateClass = {
  property: 'py-1 text-center',
}

const cleanItem = ref({})

onBeforeMount(() => {

  for (const property in props.item) {
    if (property !== props.exclude && property !== 'id') {
      cleanItem.value[property] = props.item[property]
    }
  }
})


</script>

<template>
  <div class="row">
    <div v-for="(property, index) in cleanItem" :key="index" class="col">
      <div class="position-relative bg-white rounded-3 pt-4" v-if="index !== exclude">
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