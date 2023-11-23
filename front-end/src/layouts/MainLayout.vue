<template>
  <q-layout view="hHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          <q-btn flat stretch to="/">
            Ticketing App
          </q-btn>
        </q-toolbar-title>

        <q-btn
          stretch
          flat
          color="primary"
          text-color="light"
          label="Logout"
          @click="logout"
        />
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
    >
      <q-list>
        <q-item-label
          header
        >
        </q-item-label>

        <EssentialLink
          v-for="link in linksList"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container class="bg-background">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
  import { ref, onMounted} from 'vue'
  import { useRouter } from 'vue-router'
  import { useQuasar } from 'quasar'
  import EssentialLink from 'components/EssentialLink.vue'
  import { api } from 'boot/axios'

  const $q = useQuasar()
  const router = useRouter()

  const leftDrawerOpen = ref(false)

  const linksList = [
    {
      title: 'Tickets',
      caption: 'Tickets Page',
      icon: 'code',
      link: '/ticket'
    },
    {
      title: 'Articles',
      caption: 'Knowledge Base Articles',
      icon: 'book',
      link: '/article'
    }
  ]

  const logout = () => {
      const response = api.post('/logout').then(response => {
        $q.cookies.set('is_authenticated', false, {path: '/'});
        router.push('/login')
      }).catch(err => {
      console.error(err);
    });
  }


  onMounted(async () => {
    await api.get('/sanctum/csrf-cookie')
    await api.get('/api/user').then(response => {
      if (response.status === 200) {
        $q.cookies.set('is_authenticated', true, {path: '/'});
      }
    })
    .catch(err => {
      if (err.response.status === 401) {
        console.log(err.response.status )
        $q.cookies.set('is_authenticated', false, {path: '/'});
      }
      console.error(err);
    });
  })

  function toggleLeftDrawer(){
    leftDrawerOpen.value = !leftDrawerOpen.value
  }

</script>
