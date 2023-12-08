<template>
  <q-page padding>
    {{ id }}
  </q-page>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { api } from "boot/axios";

const props = defineProps(["id"]);

onMounted(() => {
  api.get("api/tickets/" + props.id).then((response) => {
    //Post
  });
});
</script>
