<template>
  <q-page padding>
    <q-table 
      v-if="rows.length>0"
      title="Articles"
      :rows="rows"
    />
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from 'boot/axios'

const props = defineProps(['id'])
const rows = ref([])

onMounted(() => {
  if (props.id !== null) {
    api.get(`/api/support-articles/${props.id}`)
    .then(response => {
      rows.value = [response.data]
    })
  } else {
    // call API for specific article. 
  }
})

</script>
