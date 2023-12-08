<template>
  <q-layout class="flex flex-center bg-background" view="hHh lpR fFf">
    <div class="column q-pa-md">
      <q-card class="bg-primary-light">
        <q-card-section class="ts-header">
          <div class="text-h3 text-center">Login</div>
        </q-card-section>

        <q-card-section align="right">
          <q-form class="row q-pa-md justify-center" @submit="submit">
            <div class="q-gutter-y-md">
              <div class="q-gutter-y-sm">
                <div class="text-h5 text-left text-bold text-light">
                  Username
                </div>
                <q-input
                  class="q-mt-none col text-dark"
                  bg-color="primary-light-2"
                  square
                  standout
                  v-model="form.username"
                  label="Username"
                  lazy-rules
                  :rules="[(val) => (val && val.length > 4) || 'Length > 4']"
                />
                <div class="text-h5 text-left text-bold text-light">
                  Password
                </div>
                <q-input
                  class="q-mt-none col text-dark"
                  color="primary"
                  text-green
                  bg-color="primary-light-2"
                  square
                  standout
                  v-model="form.password"
                  label="Password"
                  lazy-rules
                  :rules="[(val) => (val && val.length > 4) || 'Length > 4']"
                />
              </div>
              <div class="row justify-center">
                <q-btn
                  class="text-dark"
                  color="primary-light-2"
                  dense
                  size="lg"
                  label="Submit"
                  type="submit"
                />
              </div>
            </div>
          </q-form>
          <div class="row justify-center">
            <q-btn
              class="text-dark"
              color="primary-light-2"
              dense
              label="Register"
              to="/register"
            />
            <q-btn
              class="text-dark"
              color="primary-light-2"
              dense
              label="TestLogin"
              @click="TestLogin"
            />
          </div>
          <div class="row justify-center">
            <q-toggle
              v-model="darkModeEnabled"
              checked-icon="dark_mode"
              color="black"
              unchecked-icon="light_mode"
              @update:model-value="darkModeMixin().setDarkMode"
            >
              <q-tooltip>
                {{ darkModeEnabled ? "Disable" : "Enable" }} Dark Mode
              </q-tooltip>
            </q-toggle>
          </div>
        </q-card-section>
      </q-card>
    </div>
  </q-layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useQuasar } from "quasar";
import { api } from "boot/axios";
import { useRouter } from "vue-router";
import { darkModeMixin } from "src/mixins/ticketSystemMixin.js";

const $q = useQuasar();
const savedDarkMode = localStorage.getItem("ticket_app.dark_mode_enabled");
const darkModeEnabled = ref(savedDarkMode ? JSON.parse(savedDarkMode) : false);
$q.dark.set(darkModeEnabled.value);

const form = ref({
  username: "",
  passsword: "",
});
const router = useRouter();

const submit = () => {
  // Login...
  const { data } = api
    .post("/login", {
      email: form.value.username,
      password: form.value.password,
    })
    .then((response) => {
      $q.cookies.set("is_authenticated", true, { path: "/" });
      router.push("/article");
    })
    .catch((err) => {
      console.error(err);
    });
};

const TestLogin = () => {
  api
    .post("/login", {
      email: "test@example.com",
      password: "password",
    })
    .then((response) => {
      $q.cookies.set("is_authenticated", true, { path: "/" });
      router.push("/article");
    })
    .catch((err) => {
      console.error(err);
    });
};

onMounted(async () => {
  await api.get("/sanctum/csrf-cookie");
  await api
    .get("/api/user")
    .then((response) => {
      if (response.status === 200) {
        $q.cookies.set("is_authenticated", true, { path: "/" });
      }
    })
    .catch((err) => {
      if (err.response.status === 401) {
        console.log(err.response.status);
        $q.cookies.set("is_authenticated", false, { path: "/" });
      }
      console.error(err);
    });
});
</script>
