<template>
  <q-page padding>
    <div class="text-h4">{{pagination}}</div>
    <q-table
      title="Tickets"
      ref="ticketTableRef"
      :rows="rows"
      v-model:pagination="pagination"
      :filter="filter"
      @request="onRequest"
    />
  </q-page>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import { api } from 'boot/axios'


  const ticketTableRef = ref()
  const rows = ref([])
  const filter = ref('')
  const pagination = ref({
      sortBy: 'id',
      descending: false,
      page: 1,
      rowsPerPage: 3,
    })


  function onRequest (props) {
    const { page, rowsPerPage, sortBy, descending } = props.pagination
    const filter = props.filter
    console.log(props);
    api.post('api/ticket-data', {
      'pagination' : props.pagination
    })
    .then(response => {
      rows.value = response.data

      //Update Pagination ref
        pagination.value.rowsNumber = rowsPerPage
        pagination.value.page = page
        pagination.value.rowsPerPage = rowsPerPage
        pagination.value.sortBy = sortBy
        pagination.value.descending = descending

    })
  }

  function getRowsNumberCount (filter) {
      if (!filter) {
        return rows.value.length
      }/*
      let count = 0
      rows.value.forEach(treat => {
        if (treat.name.includes(filter)) {
          ++count
        }
      }) */
      return rows.value.length
    }



  onMounted(() => {
    api.post('api/ticket-data', {
      'pagination' : pagination.value
    })
    .then(response => {
      rows.value = response.data
    })
  })
</script>
