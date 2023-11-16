<template>
  <q-page padding>
    <q-table 
      title="Articles"
      :rows="rows"
    />
  </q-page>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from 'boot/axios'

const rows = ref([])

onMounted(() => {
  api.get('/support-articles')
  .then(response => {
    rows.value = response.data
  })
})

</script>
