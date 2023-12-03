<template >
  <q-page padding  class="row justify-center">

    <q-card class="col-sm-12 col-md-6">
      <q-card-section class="bg-primary text-light">
        <q-toolbar class="flex items-left">
          <q-toolbar-title class="text-h5 text-weight-bold q-pr-xs">Ticket: {{ id }}</q-toolbar-title>
          <q-btn label="Submit" color="primary-light" @click="submitComment"></q-btn>
        </q-toolbar>

      </q-card-section>
      <q-card-section class="text-light">
        <div class="column ">
          <div class="col flex items-left">
            <div class="text-subtitle1 text-dark text-weight-bold q-pr-xs">Name:</div>
            <div class="text-subtitle2 text-dark self-center">{{name}}</div>

          </div>

          <div class="col text-subtitle1 text-dark text-weight-bold ">Description</div>
          <div class="col-12 q-px-lg">
            <q-input
            v-model="description"
            filled
            type="textarea"
            />
          </div>
          <div class="col q-pt-sm">
            <div class="col flex items-left q-gutter-y-sm">
              <div class="flex no-wrap">
                <div class="text-subtitle1 text-dark text-weight-bold q-pr-sm ">Priority:</div>
                <div class="text-subtitle2 text-dark self-center">
                  <q-btn-dropdown class='q-mr-md text-left' color="primary-light"  style="min-width: 120px;" dense :label="selectedPriority" auto-close >
                    <q-list>
                      <q-item clickable v-close-popup @click="selectPriority(item)" v-for="(item, index) in priorityArray" :key="index">
                        <q-item-section>
                          <q-item-label  class="text-left">{{item}}</q-item-label>
                        </q-item-section>
                      </q-item>
                    </q-list>
                  </q-btn-dropdown>
                </div>
              </div>
              <div class="flex">
                <div class="text-subtitle1 text-dark text-weight-bold q-pr-sm ">Status:</div>
                <div class="text-subtitle2 text-dark self-center">
                  <q-btn-dropdown class='q-mr-md text-left' color="primary-light"  style="min-width: 120px;" dense :label="selectedStatus" auto-close >
                    <q-list>
                      <q-item clickable v-close-popup @click="selectStatus(item)" v-for="(item, index) in statusArray" :key="index">
                        <q-item-section>
                          <q-item-label  class="text-left">{{item}}</q-item-label>
                        </q-item-section>
                      </q-item>
                    </q-list>
                  </q-btn-dropdown>
                </div>
              </div>
              <div class="flex">
                <div class="text-subtitle1 text-dark text-weight-bold q-pr-sm ">Assigned:</div>
                <div class="text-subtitle2 text-dark self-center">
                  <q-btn-dropdown class='q-mr-md text-left' color="primary-light"  style="min-width: 120px;" dense :label="selectedAssigned" auto-close >
                    <q-list>
                      <q-item clickable v-close-popup @click="selectAssigned(item)" v-for="(item, index) in assignedArray" :key="index">
                        <q-item-section>
                          <q-item-label  class="text-left">{{item}}</q-item-label>
                        </q-item-section>
                      </q-item>
                    </q-list>
                  </q-btn-dropdown>
                </div>
              </div>
              <div class="flex">
                <div class="text-subtitle1 text-dark text-weight-bold q-pr-sm ">Product:</div>
                <div class="text-subtitle2 text-dark self-center">
                  <q-btn-dropdown class='q-mr-md text-left' color="primary-light"  style="min-width: 120px;" dense :label="selectedProduct" auto-close >
                    <q-list>
                      <q-item clickable v-close-popup @click="selectProduct(item)" v-for="(item, index) in productArray" :key="index">
                        <q-item-section>
                          <q-item-label  class="text-left">{{item}}</q-item-label>
                        </q-item-section>
                      </q-item>
                    </q-list>
                  </q-btn-dropdown>
                </div>
              </div>
            </div>
          </div>
          <div class="col q-pt-sm">
            <q-separator inset />
            <div class="column">
              <div class="col text-subtitle1 text-dark text-weight-bold ">Comments</div>
              <div class="col-12 q-px-lg">
                <q-input
                v-model="commentField"
                filled
                type="textarea"
                />
              </div>
              <div class="col-12 self-end q-pr-xl q-pt-sm">
                <q-btn label="Submit" color="primary-light" @click="submitComment"></q-btn>
              </div>
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

  const priorityArray = ref(['High', 'Low'])
  const selectedPriority = ref('High')

  const statusArray = ref(['Open', 'Closed'])
  const selectedStatus = ref('Open')

  const assignedArray = ref(['George', 'Tom'])
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


  onMounted(() => {

    api.get('api/ticket-options').then(response =>{
      const data = response.data;
      console.log(data);
      statusArray.value = data['Status'].map(item => item.name)
      priorityArray.value = data['Priority'].map(item => item.name)
      assignedArray.value = data['Users'].map(item => item.name)
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
      comments.value = data
      console.log(data);
    })
  })
</script>

