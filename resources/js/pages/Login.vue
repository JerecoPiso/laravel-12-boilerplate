<template>
  <div class="min-h-screen flex bg-white">
    <!-- Brand Panel -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-linear-to-br from-blue-950 via-blue-900 to-blue-700">
      <!-- Decorative grid -->
      <svg class="absolute inset-0 w-full h-full opacity-[0.07]" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1" />
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#grid)" />
      </svg>
      <!-- Glow accents -->
      <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500/30 rounded-full blur-3xl"></div>
      <div class="absolute bottom-0 right-0 w-[28rem] h-[28rem] bg-blue-400/20 rounded-full blur-3xl"></div>

      <div class="relative z-10 flex flex-col justify-between p-12 xl:p-16 w-full text-white">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-lg bg-white/10 border border-white/20 flex items-center justify-center backdrop-blur-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
            </svg>
          </div>
          <span class="font-semibold tracking-tight text-lg">{{ appName }}</span>
        </div>

        <div class="max-w-md">
          <h1 class="text-4xl xl:text-[2.75rem] font-bold leading-tight tracking-tight mb-5">
            A production-ready foundation for your next Laravel app.
          </h1>
          <p class="text-blue-100/80 text-base leading-relaxed mb-10">
            Authentication, API structure, and a Vue 3 admin shell — wired up and ready so you can start building features on day one.
          </p>

          <ul class="space-y-4">
            <li v-for="feature in features" :key="feature" class="flex items-center gap-3 text-sm text-blue-50/90">
              <span class="flex items-center justify-center w-5 h-5 rounded-full bg-white/15 shrink-0">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
              </span>
              {{ feature }}
            </li>
          </ul>
        </div>

        <p class="text-xs text-blue-200/60">© {{ getYear() }} {{ appName }}. All rights reserved.</p>
      </div>
    </div>

    <!-- Form Panel -->
    <div class="flex-1 flex items-center justify-center px-6 py-12 sm:px-12">
      <div class="w-full max-w-sm">
        <!-- Mobile brand mark -->
        <div class="flex lg:hidden items-center gap-3 mb-10">
          <div class="w-10 h-10 rounded-lg bg-linear-to-br from-blue-600 to-blue-800 flex items-center justify-center shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
            </svg>
          </div>
          <span class="font-semibold tracking-tight text-lg text-slate-900">{{ appName }}</span>
        </div>

        <div class="mb-8">
          <h2 class="text-2xl font-bold text-slate-900 tracking-tight mb-1.5">Welcome back</h2>
          <p class="text-slate-500 text-sm">Sign in to continue to your dashboard</p>
        </div>

        <form @submit.prevent="login" class="space-y-5">
          <div class="flex flex-col gap-1.5">
            <label for="email" class="text-sm font-medium text-slate-700">Email address</label>
            <IconField>
              <InputIcon class="pi pi-envelope text-slate-400" />
              <InputText id="email" v-model="loginForm.email" type="email" placeholder="you@example.com" fluid required autofocus />
            </IconField>
          </div>

          <div class="flex flex-col gap-1.5">
            <div class="flex items-center justify-between">
              <label for="password" class="text-sm font-medium text-slate-700">Password</label>
              <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-700 transition">Forgot password?</a>
            </div>
            <Password
              id="password"
              v-model="loginForm.password"
              placeholder="••••••••"
              :feedback="false"
              toggleMask
              fluid
              inputClass="w-full"
              required
            />
          </div>

          <div class="flex items-center gap-2">
            <Checkbox v-model="rememberMe" inputId="rememberMe" binary />
            <label for="rememberMe" class="text-sm text-slate-600 cursor-pointer select-none">Remember me for 30 days</label>
          </div>

          <Message v-if="errorMessage" severity="error" :closable="false" class="text-sm">{{ errorMessage }}</Message>

          <Button type="submit" :loading="isLoading" :label="isLoading ? 'Signing in...' : 'Sign in'" fluid class="bg-linear-to-r from-blue-600 to-blue-700 border-0 font-semibold" />
        </form>

        <p class="mt-8 text-center text-xs text-slate-400 lg:hidden">© {{ getYear() }} {{ appName }}. All rights reserved.</p>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAppToast } from "@/composables/toast";
import axios from "axios";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import Password from "primevue/password";
import Checkbox from "primevue/checkbox";
import Message from "primevue/message";

interface LoginForm {
  email: string;
  password: string;
}

const appName = import.meta.env.VITE_APP_NAME ?? "Laravel 12 Boilerplate";
const features = [
  "Laravel 12 API with Sanctum authentication",
  "Vue 3 + TypeScript + Pinia admin shell",
  "PrimeVue components, ready to extend",
];
const toast = useAppToast();
const loginForm = ref<LoginForm>({
  email: "",
  password: "",
});
const router = useRouter();
const isLoading = ref<boolean>(false);
const rememberMe = ref<boolean>(false);
const baseUrl = import.meta.env.VITE_APP_API_URL;
const errorMessage = ref<string>("");

const login = async () => {
  if (!loginForm.value.email || !loginForm.value.password) {
    errorMessage.value = "Please fill in all fields";
    return;
  }
  isLoading.value = true;
  errorMessage.value = "";

  try {
    await axios.get(`${baseUrl}sanctum/csrf-cookie`);
    const response = await axios.post(`${baseUrl}user/login`, loginForm.value);
    if (response.status == 200) {
      localStorage.removeItem("isLoggedout");
      router.push({ name: "Dashboard" });
    }
  } catch (error: any) {
    const message = error.response?.data?.message || "Unable to sign in";
    errorMessage.value = message;
    toast.error(message);
    isLoading.value = false;
  }
};

const getYear = () => {
  const date = new Date();
  return date.getFullYear();
};
</script>
