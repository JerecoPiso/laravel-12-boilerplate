import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useAuthStore = defineStore('auth', () => {
    const baseUrl = import.meta.env.VITE_APP_API_URL;
    const user = ref(null)
    const getUser = async () => {
        try {
            const response = await axios.get(`${baseUrl}api/user`, { withCredentials: true });
            user.value = response.data;
        } catch (err) {
            user.value = null;
        }
    }
    return { user, getUser }
})
