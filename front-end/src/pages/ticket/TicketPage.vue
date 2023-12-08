<template >
  <q-page padding  class="row justify-center">
    <q-card class="col-sm-12 col-md-10">
      <q-card-section class="bg-primary text-light">
        <q-toolbar class="flex items-left">
          <q-toolbar-title class="text-h5 text-weight-bold q-pr-xs">
            Ticket: {{ id }}

          </q-toolbar-title>
          <q-btn label="Cancel" color="primary-light" @click="cancelChanges"></q-btn>
          <q-btn class="q-ml-sm" label="Save" color="primary-light" @click="saveChanges"></q-btn>
        </q-toolbar>

      </q-card-section>
      <q-card-section class="text-light">
        <div class="column ">
          <div class="col flex items-left cursor-pointer">
            <div class="text-subtitle1 text-dark text-weight-bold q-pr-xs cursor-pointer">Name:</div>
            <div class="text-subtitle2 text-dark self-center">{{name}}</div>
            <q-popup-edit v-model="name" auto-save v-slot="scope">
              <q-input v-model="scope.value" dense autofocus counter @keyup.enter="scope.set" />
            </q-popup-edit>
          </div>

          <div class="col text-subtitle1 text-dark text-weight-bold ">Description</div>
          <div class="col-12 q-px-lg">
            <q-editor class="text-dark" v-model="description" min-height="10rem" />
          </div>
          <div class="col q-pt-sm">
            <div class="col flex justify-center q-gutter-y-sm q-gutter-x-sm">
              <div class="flex no-wrap">
                <div class="text-subtitle2 text-dark self-center">
                  <q-select
                    filled
                    v-model="selectedPriority"
                    input-debounce="0"
                    label="Priority"
                    label-color="primary"
                    :options="priorityArray"
                    style="width: 250px"
                    behavior="menu"
                    dense
                  />
                </div>
              </div>
              <div class="flex">
                <div class="text-subtitle2 text-dark self-center">
                  <q-select
                    filled
                    v-model="selectedStatus"
                    input-debounce="0"
                    label="Status"
                    label-color="primary"
                    :options="statusArray"
                    style="width: 250px"
                    behavior="menu"
                    dense
                  />
                </div>
              </div>
              <div class="flex">
                <div class="text-subtitle2 text-dark self-center">
                  <q-select
                    filled
                    v-model="selectedAssigned"
                    use-input
                    input-debounce="0"
                    label="Assigned Tech"
                    label-color="primary"
                    :options="assignedOptions"
                    @filter="filterFn"
                    style="width: 250px"
                    behavior="menu"
                    dense
                  >
                    <template v-slot:no-option>
                      <q-item>
                        <q-item-section class="text-grey">
                          No results
                        </q-item-section>
                      </q-item>
                    </template>
                  </q-select>
                </div>
              </div>
              <div class="flex">
                <div class="text-subtitle2 text-dark self-center">
                  <q-select
                    filled
                    v-model="selectedProduct"
                    input-debounce="0"
                    label="Status"
                    label-color="primary"
                    :options="productArray"
                    style="width: 250px"
                    behavior="menu"
                    dense
                  />
                </div>
              </div>
            </div>
          </div>
          <div class="col q-pt-sm">
            <q-separator inset />
            <div class="column q-mt-sm">
              <div class="col text-subtitle1 text-dark text-weight-bold ">Comments</div>
              <div class="col-12 q-px-lg">
              <q-editor class="text-dark" v-model="commentField" min-height="5rem" />
              </div>
              <div class="col-12 self-end q-pr-xl q-pt-sm">
                <q-btn label="Submit" color="primary-light" @click="submitComment"></q-btn>
              </div>

              <q-separator inset class="q-mt-sm"/>
            </div>
          </div>
          <div class="col q-pt-sm">
            <div class="row q-pt-md items-center justify-center" v-for="(item) in comments" :key="item.id">
              <q-card class="col-sm-12 col-md-9">
                <q-card-section class="flex justify-between q-pa-xs bg-primary-light-2 text-dark">
                  <q-item class="column q-pa-xs">
                    <div class="col text-h6">Comment #: {{ item.id }}</div>
                    <div class="col text-subtitle2">Posted By: {{ item.user_name }}</div>
                  </q-item>
                  <q-item class="">
                    <div class="text-subtitle2">Date: {{item.created_at}}</div>
                  </q-item>
                </q-card-section>
                <q-card-section class="text-dark" >
                  <div style=" overflow-wrap: break-word;">{{ item.text }}</div>
                </q-card-section>
              </q-card>
            </div>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
  import { ref, onMounted } from 'vue'
  import { api } from 'boot/axios'
  import { useQuasar } from 'quasar';

  const $q = useQuasar();


  const props = defineProps(['id'])
  const name = ref('')
  const description = ref('')
  const commentField = ref('')


  const priorityMap = ref()
  const priorityArray = ref(['High', 'Low'])
  const selectedPriority = ref('High')

  const statusMap = ref()
  const statusArray = ref(['Open', 'Closed'])
  const selectedStatus = ref('Open')

  const assignedMap = ref()
  const assignedArray = ref(['George', 'Tom'])
  const assignedOptions = ref()
  const selectedAssigned = ref('George')

  const productArray = ref(['Prod 1', 'Prod 2'])
  const selectedProduct = ref('Prod 1')

  const comments = ref([])


  function selectPriority(item)
  {
    selectedPriority.value = item
  }

  function selectStatus(item)
  {
    selectedStatus.value = item
  }

  function selectAssigned(item)
  {
    selectedAssigned.value = item
  }

  function selectProduct(item)
  {
    selectedProduct.value = item
  }


  function submitComment(item)
  {
    const userID = $q.cookies.get('user_id');
    const payload = {
      user_id: userID,
      commentable_type: "ticket",
      commentable_id: props.id,
      text: commentField.value,

    }

    api.post("api/comments", payload).then(response =>{
      api.post('api/comments-ticket', {
      id: props.id
      })
      .then(response => {
        const data = response.data;
        comments.value = data
        console.log(data);
      })
      commentField.value = ''
    })

  }

  function saveChanges()
  {
    const userID = $q.cookies.get('user_id');
    api.put('api/tickets/' + props.id, {
      assigned_user_id : assignedMap.value[selectedAssigned.value],
      client_user_id : 1,
      product_id : null,
      ticket_status_id : statusMap.value[selectedStatus.value],
      ticket_priority_id : priorityMap.value[selectedPriority.value],
      name : name.value,
      description : description.value,
      date_closed : null,
    })
    .then(response => {
      if(response.status === 200)
      {
        $q.notify({
          type: "positive",
          message: "Ticket updated successfully.",
        })
      }
    })

  }

  function cancelChanges(item)
  {
    loadTicket()
  }


  onMounted(() => {
    loadTicket()
  })

  function loadTicket(){
    api.get('api/ticket-options').then(response =>{
      const data = response.data;

      statusArray.value = data['Status'].map(item => item.name)
      statusMap.value = Object.fromEntries(data['Status'].map(row => [row.name, row.id]))

      priorityArray.value = data['Priority'].map(item => item.name)
      priorityMap.value = Object.fromEntries(data['Priority'].map(row => [row.name, row.id]))

      assignedArray.value = data['Users'].map(item => item.name)
      assignedMap.value = Object.fromEntries(data['Users'].map(row => [row.name, row.id]))
    })

    api.post('api/ticket-data-single', {
      id: props.id
    })
    .then(response => {
      const data = response.data;
      console.log(data);
      name.value = data.Name;
      description.value = data.Description;
      selectedPriority.value = data.Priority;
      selectedStatus.value = data.Status;
      selectedAssigned.value = data.Assigned;
    })

    api.post('api/comments-ticket', {
      id: props.id
    })
    .then(response => {
      const data = response.data;
      comments.value = data.reverse()
      console.log(data);
    })
  }

  function filterFn (val, update) {
    if (val === '') {
      update(() => {
        assignedOptions.value = assignedArray.value
      })
      return
    }

    update(() => {
      const needle = val.toLowerCase()
      assignedOptions.value = assignedArray.value.filter(v => v.toLowerCase().indexOf(needle) > -1)
    })
  }


</script>

