<template>
  <q-page padding>
    <q-table
      title="Tickets"
      :rows="rows"
    />
  </q-page>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import { api } from 'boot/axios'

  const rows = ref([])

  onMounted(() => {
    api.get('/tickets')
    .then(response => {
      rows.value = response.data
    })
  })
</script>
