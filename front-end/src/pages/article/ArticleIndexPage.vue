<template>
    <!-- <q-page padding>
      <q-table 
        v-if="rows.length>0"
        title="Articles"
        :rows="rows"
      />
    </q-page> -->
    <q-page padding  >
    <CustomTable :tableProps="tableProps"></CustomTable>
  </q-page>
</template>
  
  <script setup>
  // import { ref, onMounted } from 'vue'
  // import { api } from 'boot/axios'
  import CustomTable from 'src/components/CustomTable.vue'

  const props = defineProps(['id'])
  const tableProps = {
    tableName: 'Articles',
    indexAPI: "api/article-data",
    unitAPI: "article",
  }
//   const props = defineProps(['id'])
  // const rows = ref([])
  
  // onMounted(() => {
  //   api.get('/api/support-articles')
  //   .then(response => {
  //   rows.value = response.data
  //   })
  // })
  
  </script>
  