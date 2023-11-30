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
  import { ref, onMounted, defineProps } from 'vue'
  import { api } from 'boot/axios'
  
//   const props = defineProps(['id'])
  const rows = ref([])
  
  onMounted(() => {
    api.get('/support-articles')
    .then(response => {
    rows.value = response.data
    })
  })
  
  </script>
  