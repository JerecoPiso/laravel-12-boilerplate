<template>
    <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-800 mb-2">Dashboard</h1>
        <p class="text-slate-500 text-sm">Welcome back{{ userName ? `, ${userName}` : '' }}. This is a placeholder dashboard for the boilerplate.</p>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { useAuthStore } from "@/store/AuthStore";

const auth = useAuthStore();
const userName = computed(() => {
    const user: any = auth.user;
    if (!user) return "";
    return user.name || "";
});
</script>
