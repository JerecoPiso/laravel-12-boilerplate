<template>
  <ConfirmDialog></ConfirmDialog>

  <div class="min-h-screen bg-linear-to-br from-slate-50 to-slate-100">
    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-sm">
      <div class="flex items-center justify-between px-4 md:px-8 py-4">
        <!-- Logo & Title -->
        <div class="flex items-center gap-3">
          <button @click="sidebarExpanded = !sidebarExpanded" class="md:hidden p-2 hover:bg-slate-100 rounded-lg transition-colors">
            <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-linear-to-br from-blue-600 to-blue-800 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
              </svg>
            </div>
            <div>
              <h1 class="text-xl font-bold text-slate-900">{{ appName }}</h1>
              <p class="text-xs text-slate-500">Admin Panel</p>
            </div>
          </div>
        </div>

        <!-- User Profile Popover -->
        <div class="flex items-center gap-4">
          <div class="relative">
            <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 p-2 hover:bg-slate-100 rounded-lg transition-colors">
              <div class="w-8 h-8 rounded-full border-2 border-blue-500 bg-linear-to-br from-blue-400 to-blue-700 flex items-center justify-center text-white text-xs font-semibold shrink-0">
                {{ userInitials }}
              </div>
              <div class="hidden sm:block text-left">
                <p class="text-sm font-semibold text-slate-900">{{ userDisplayName }}</p>
                <p class="text-xs text-slate-500">{{ userEmail }}</p>
              </div>
              <svg class="w-4 h-4 text-slate-600 transition-transform" :class="{ 'rotate-180': profileOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
              </svg>
            </button>

            <!-- Popover Menu -->
            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div v-if="profileOpen" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden z-50">
                <div class="bg-linear-to-r from-blue-600 to-blue-800 px-6 py-4">
                  <div class="w-12 h-12 rounded-full border-3 border-white mb-3 bg-white/20 flex items-center justify-center text-white font-semibold">
                    {{ userInitials }}
                  </div>
                  <p class="text-white font-semibold">{{ userDisplayName }}</p>
                  <p class="text-blue-100 text-sm">{{ userEmail }}</p>
                </div>

                <div class="border-t border-slate-200"></div>

                <button @click="handleLogout" class="w-full px-6 py-3 text-left text-red-600 hover:bg-red-50 flex items-center gap-3 transition-colors font-medium">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                  Logout
                </button>
              </div>
            </transition>

            <!-- Backdrop -->
            <div v-if="profileOpen" @click="profileOpen = false" class="fixed inset-0 z-40"></div>
          </div>
        </div>
      </div>
    </header>

    <div class="flex">
      <!-- Sidebar -->
      <aside
        class="fixed min-h-[90vh] md:static inset-y-0 left-0 z-30 bg-white border-r border-slate-200 transform transition-all duration-300 mt-16 md:mt-0"
        :class="{ 'w-64': sidebarExpanded, 'md:w-20': !sidebarExpanded }"
      >
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
          <h2 v-if="sidebarExpanded" class="text-sm font-bold text-slate-900 uppercase tracking-wider">Menu</h2>
          <button @click="sidebarExpanded = !sidebarExpanded" class="p-2 hover:bg-slate-100 rounded-lg transition-colors hidden md:flex items-center justify-center">
            <svg class="w-5 h-5 text-slate-700 transition-transform" :class="{ 'rotate-180': !sidebarExpanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
        </div>
        <nav class="p-4 space-y-2">
          <template v-for="item in navItems" :key="item.name">
            <!-- Parent link with dropdown -->
            <div v-if="item.children">
              <button
                @click="toggleSubmenu(item)"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-slate-700 hover:bg-slate-100"
                :title="!sidebarExpanded ? item.label : ''"
              >
                <span v-html="item.icon" class="text-gray-600"></span>
                <span v-if="sidebarExpanded" class="font-medium flex-1 text-left">{{ item.label }}</span>
                <svg
                  v-if="sidebarExpanded"
                  class="w-4 h-4 transition-transform shrink-0"
                  :class="{ 'rotate-180': expandedMenu === item.name }"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div v-if="sidebarExpanded && expandedMenu === item.name" class="mt-1 ml-4 space-y-1 border-l-2 border-slate-200 pl-3">
                <router-link
                  v-for="child in item.children"
                  :key="child.name"
                  :to="{ name: child.name }"
                  class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all"
                  :class="activeNav === child.name ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-600 hover:bg-slate-100'"
                >
                  <span>{{ child.label }}</span>
                </router-link>
              </div>
            </div>

            <!-- Regular link -->
            <router-link
              v-else
              :to="{ name: item.name }"
              class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all"
              :class="activeNav === item.name ? 'bg-linear-to-r from-blue-600 to-blue-800 text-white shadow-md' : 'text-slate-700 hover:bg-slate-100'"
              :title="!sidebarExpanded ? item.label : ''"
            >
              <span v-html="item.icon" :class="activeNav === item.name ? 'text-white' : 'text-gray-600'"></span>
              <span v-if="sidebarExpanded" class="font-medium">{{ item.label }}</span>
            </router-link>
          </template>
        </nav>
        <div class="fixed bottom-0 left-0 right-0 p-4 border-t border-slate-200 bg-slate-50">
          <p class="text-xs text-slate-600 text-center">{{ appName }}</p>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-4 md:p-8">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from "vue";
import ConfirmDialog from "primevue/confirmdialog";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";
import { useConfirmToast } from "@/composables/confirm";
import { useAuthStore } from "@/store/AuthStore";

const { showConfirm } = useConfirmToast();
const router = useRouter();
const route = useRoute();
const auth = useAuthStore();
const appName = import.meta.env.VITE_APP_NAME ?? "Laravel 12 Boilerplate";

const userDisplayName = computed(() => {
  const user: any = auth.user;
  if (!user) return "Guest";
  if (user.name) return user.name;
  return [user.firstname, user.lastname].filter(Boolean).join(" ") || "Guest";
});
const userEmail = computed(() => (auth.user as any)?.email || "");
const userInitials = computed(() => {
  const name = userDisplayName.value;
  if (!name || name === "Guest") return "?";
  return name
    .split(" ")
    .filter(Boolean)
    .slice(0, 2)
    .map((part: string) => part.charAt(0).toUpperCase())
    .join("");
});

const profileOpen = ref(false);
const sidebarExpanded = ref(true);
const expandedMenu = ref<string | null>(null);
const activeNav = computed(() => route.name);

const dashboardIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>';
const usersIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" /></svg>';
const settingsIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>';

interface NavItem {
  name: string;
  label: string;
  icon: string;
  children?: { name: string; label: string }[];
}

const navItems: NavItem[] = [
  { name: "Dashboard", label: "Dashboard", icon: dashboardIcon },
  { name: "Users", label: "Users", icon: usersIcon },
  {
    name: "Settings",
    label: "Settings",
    icon: settingsIcon,
    children: [{ name: "SampleSetting", label: "Sample Setting" }],
  },
];

const toggleSubmenu = (item: NavItem) => {
  if (!sidebarExpanded.value) {
    sidebarExpanded.value = true;
  }
  expandedMenu.value = expandedMenu.value === item.name ? null : item.name;
};

watch(
  () => route.name,
  (name) => {
    const parent = navItems.find((item) => item.children?.some((child) => child.name === name));
    if (parent) {
      expandedMenu.value = parent.name;
    }
  },
  { immediate: true }
);

const handleLogout = () => {
  profileOpen.value = false;
  showConfirm({
    message: "Are you sure you want to logout?",
    header: "Confirmation",
    onAccept: async () => {
      const baseUrl = import.meta.env.VITE_APP_API_URL;
      await axios.post(`${baseUrl}api/user/logout`);
      auth.user = null;
      localStorage.setItem("isLoggedout", "true");
      router.push({ name: "Login" });
    },
  });
};
</script>

<style scoped>
* {
  transition-property: background-color, border-color, color, fill, stroke;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style>
