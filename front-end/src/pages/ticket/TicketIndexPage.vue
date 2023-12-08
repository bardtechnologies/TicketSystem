<template>
  <q-page padding>
    <CustomTable :tableProps="tableProps"></CustomTable>
  </q-page>
</template>

<script setup>
import CustomTable from "src/components/CustomTable.vue";

const props = defineProps(["id"]);
const tableProps = {
  tableName: "Tickets",
  indexAPI: "api/ticket-data",
  unitAPI: "ticket",
};
</script>
