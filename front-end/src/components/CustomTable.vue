<template>
    <q-table
    :title=tableProps.tableName
    class="my-sticky-header-table"
    ref="ticketTableRef"
    no-data-label="I didn't find anything for you"
    no-results-label="The filter didn't uncover any results"
    dense
    :rows-per-page-options="[]"
    binary-state-sort
    :filter="filter"
    :rows="rows"
    :columns="columns"
    row-key="name"
    v-model:pagination="pagination"
    @request="onRequest"
    v-scroll="onScroll"
    column-sort-order="da"
    >
    <template v-slot:top-right>
      <div class="row q-pt-xs">
          <div class="col q-pr-sm text-subtitle1 text-right">Search By:</div>
          <div class="col">
            <q-btn-dropdown class='q-mr-md text-left' color="primary"  style="min-width: 120px;" dense :label="selectedField" auto-close v-model="isDropdownOpen">
              <q-list>
                <q-item clickable v-close-popup @click="selectSearchField(item)" v-for="(item, index) in keysArray" :key="index">
                  <q-item-section>
                    <q-item-label  class="text-left">{{item}}</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-btn-dropdown>
          </div>
          <div class="col">
            <q-input class="bg-primary-light-2" borderless dense debounce="300" v-model="filter" placeholder="Search">
              <template v-slot:append>
                <q-icon name="search" />
              </template>
            </q-input>
          </div>

      </div>


    </template>
      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td class="my-box cursor-pointer q-hoverable" @click="openUnit(props.row['ID'])" v-for="(item) in keysArray" :key="item" :props="props">
            <div>{{ props.row[item]}}</div>
          </q-td>
        </q-tr>
      </template>
      <template v-slot:pagination >
        <q-pagination
          v-model="pagination.page"
          @update:model-value="changePage"
          direction-links
          boundary-numbers
          color="white"
          active-color="primary"
          dense
          size="md"
          gutter="xs"
          :max="lastPage"
          :max-pages="6"
        />
      </template>
    </q-table>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import { api } from 'boot/axios'
  import { useRouter } from 'vue-router'

  /*
  This is the prop that is passed into this table
  Replace Tickets with your data

  const tableProps = {
    tableName: 'Tickets',
    indexAPI: "api/ticket-data",
    unitAPI: "ticket",
  }
  */
  const props = defineProps(['tableProps'])

  const router = useRouter()

  const lastPage = ref(1)
  const ticketTableRef = ref()
  const filter = ref('')

  const rows = ref([])
  const columns = ref([])

  const pagination = ref({
      sortBy: 'id',
      descending: false,
      page: 1,
      rowsPerPage: 50,
      rowsNumber: 10
  })
  const keysArray = ref([])
  const selectedField = ref('Name')
  const isDropdownOpen = ref(false);

  function selectSearchField(item)
  {
    this.selectedField = item;
  }

  function openUnit(itemID)
  {
      router.push(props.tableProps.unitAPI + '/' + itemID)
  }

  function setTable(response)
  {
    rows.value  = response.data
    lastPage.value = response.last_page
  }

  function changePage(newPage)
  {
    pagination.value.page = newPage;
    api.post(props.tableProps.indexAPI + '?page=' + newPage, {
      'pagination' : pagination.value,
      'selectedfield' : selectedField.value,
      'filter' : filter.value
    })
    .then(response => {
      setTable(response.data)
    })
  }

  function onRequest (requestProps) {

    const { page, rowsPerPage, sortBy, descending } = requestProps.pagination
    const filter = requestProps.filter

    api.post(props.tableProps.indexAPI, {
      'pagination' : requestProps.pagination,
      'selectedfield' : selectedField.value,
      'filter' : filter
    })
    .then(response => {
      setTable(response.data)

      //Update Pagination ref
        pagination.value.rowsNumber = rowsPerPage
        pagination.value.page = page
        pagination.value.rowsPerPage = rowsPerPage
        pagination.value.sortBy = sortBy
        pagination.value.descending = descending

    })
  }

  function onScroll(position) {
    // Check if the dropdown is open and close it on scroll
    if (isDropdownOpen.value) {
      isDropdownOpen.value = false
    }
  }

  onMounted(() => {
    api.post(props.tableProps.indexAPI, {
      'pagination' : pagination.value,
      'selectedfield' : selectedField.value,
      'filter' : filter.value
    })
    .then(response => {
      keysArray.value = Object.keys(response.data.data[0]);
      keysArray.value.forEach(element => {
        columns.value.push({ name: element, align: 'center', label: element, field: element, sortable: true })
      });
      setTable(response.data)
    })
  })
</script>


<style lang="scss">

.my-sticky-header-table{
  /* height or max-height is important */
  height: 720px;
}


.q-table__top,
.q-table__bottom,
thead tr:first-child th{
  /* bg color is important for th; just specify one */
  background-color: $primary-light;
  color: white;
}

thead tr th{
  position: sticky;
  z-index: 1;
}

thead tr:first-child th{
  top: 0;
}


/* this is when the loading indicator appears */
.q-table--loading thead tr:last-child th{
  /* height of all previous header rows */
  top: 48px;
}


/* prevent scrolling behind sticky top row on focus */
tbody{
  /* height of all previous header rows */
  scroll-margin-top: 48px;
}

</style>
