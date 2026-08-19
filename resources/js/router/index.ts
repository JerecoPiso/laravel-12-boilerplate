import { createRouter, createWebHistory, RouterView } from "vue-router";
import { useAuthStore } from "@/store/AuthStore";

const router = createRouter({
    history: createWebHistory(import.meta.env.VITE_APP_URL),
    routes: [
        {
            path: '/login',
            name: 'Login',
            component: () => import("../pages/Login.vue")
        },
        {
            path: "/",
            name: "Authenticated",
            component: () => import("../pages/authenticated/Authenticated.vue"),
            meta: { requiresAuth: true },
            children: [
                { path: "", name: "Dashboard", component: () => import("../pages/authenticated/Dashboard.vue") },
                { path: "users", name: "Users", component: () => import("../pages/authenticated/Users.vue") },
                {
                    path: "settings",
                    component: RouterView,
                    children: [
                        { path: "sample", name: "SampleSetting", component: () => import("../pages/authenticated/settings/SampleSetting.vue") },
                    ],
                },
            ],
        },
    ]
})

// Navigation Guard
router.beforeEach(async (to, from, next) => {
    try {
        const auth = useAuthStore();
        const isLoggedOut = localStorage.getItem("isLoggedout");
        if (!auth.user && !isLoggedOut) {
            await auth.getUser();
        }
        if (to.name === 'Login' && auth.user) {
            return next({ name: 'Dashboard' });
        }
        if (to.meta.requiresAuth && !auth.user) {
            return next({ name: 'Login' });
        }
        next();
    } catch (err) {
        if (to.meta.requiresAuth) {
            next({ name: 'Login' });
        } else if (to.name === 'Login') {
            next();
        } else {
            next();
        }
    }
});

export default router;
