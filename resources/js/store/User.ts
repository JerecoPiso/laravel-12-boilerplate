import { defineStore } from "pinia";
import { ref } from "vue";
import { User } from "@/interface/Interfaces";
import axios from "axios";

export const useUserStore = defineStore("user", () => {
    const baseUrl = import.meta.env.VITE_APP_API_URL;
    const users = ref<User[]>([])
    const user = ref<User>({
        pid: '',
        name: '',
        email: '',
        password: ''
    })
    const create = async (data: User) => {
        await axios.post(`${baseUrl}api/user/register`, data);
        read();
    }
    const read = async () => {
        const response = await axios.get(`${baseUrl}api/user/list`);
        users.value = response.data.data;
    }
    const view = async (pid: string) => {
        const response = await axios.get(`${baseUrl}api/user/${pid}`);
        user.value = response.data.data;
    }
    const update = async (data: User) => {
        await axios.put(`${baseUrl}api/user/${data.pid}`, data);
        read();
    }
    const archive = async (pid: string) => {
        await axios.delete(`${baseUrl}api/user/${pid}`);
        read();
    }
    return {
        archive,
        create,
        read,
        view,
        update,
        users,
        user
    }
})
