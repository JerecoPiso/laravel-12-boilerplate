<template>
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
        <Dialog v-model:visible="orderModal" modal :style="{ width: '48vw' }"
            :breakpoints="{ '1199px': '75vw', '575px': '95vw' }"
            :pt="{ header: { class: 'border-b border-slate-100 pb-4' } }">
            <template #header>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-linear-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-sm">
                        <i class="pi pi-users text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-800">{{ isUpdate ? 'Edit User' : 'New User' }}</h2>
                        <p class="text-xs text-slate-400 mt-0.5">{{ isUpdate ? 'Update the user\'s account details' : 'Fill in the user details below' }}</p>
                    </div>
                </div>
            </template>
            <form @submit.prevent="isUpdate ? update() : create()" class="flex flex-col gap-5 pt-2">
                <div class="grid grid-cols-2 gap-x-4 gap-y-4">
                    <div v-if="!isUpdate" class="col-span-2 flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-slate-700">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <InputText type="email" v-model="userInfo.email" placeholder="user@example.com" fluid required class="text-sm" />
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-slate-700">
                            First Name <span class="text-red-400">*</span>
                        </label>
                        <InputText v-model="userInfo.firstname" placeholder="First name" fluid required class="text-sm" />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-slate-700">Middle Name</label>
                        <InputText v-model="userInfo.middlename" placeholder="Middle name" fluid class="text-sm" />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-slate-700">
                            Last Name <span class="text-red-400">*</span>
                        </label>
                        <InputText v-model="userInfo.lastname" placeholder="Last name" fluid required class="text-sm" />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-slate-700">Suffix</label>
                        <InputText v-model="userInfo.suffix" placeholder="e.g. Jr., Sr., III" fluid class="text-sm" />
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-slate-700">Gender</label>
                        <Select v-model="userInfo.gender" :options="genders" placeholder="Select gender" fluid class="text-sm" />
                    </div>
                    <div class="col-span-2 flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-slate-700">Date of Birth</label>
                        <DatePicker v-model="userInfo.date_of_birth" dateFormat="yy-mm-dd" placeholder="YYYY-MM-DD" fluid class="text-sm" />
                    </div>
                    <div v-if="!isUpdate" class="col-span-2 flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-slate-700">
                            Password <span class="text-red-400">*</span>
                        </label>
                        <Password v-model="userInfo.password" fluid :feedback="false" toggleMask required />
                    </div>
                </div>
                <div class="flex gap-2 pt-1">
                    <Button
                        type="button"
                        label="Cancel"
                        severity="secondary"
                        outlined
                        fluid
                        @click="orderModal = false"
                    />
                    <Button
                        type="submit"
                        :label="isUpdate ? 'Update User' : 'Save User'"
                        fluid
                        class="bg-linear-to-r from-indigo-500 to-blue-600 border-0"
                    />
                </div>
            </form>
        </Dialog>

        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-linear-to-r from-slate-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-linear-to-br from-indigo-500 to-blue-600 flex items-center justify-center shadow-md">
                    <i class="pi pi-users text-white"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Users</h3>
                    <p class="text-xs text-slate-400">Manage system user accounts</p>
                </div>
            </div>
            <button
                type="button"
                @click="orderModal = true"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg transition-all duration-200 bg-linear-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white text-sm font-medium shadow-md hover:shadow-lg active:scale-95"
            >
                <i class="pi pi-plus-circle"></i>
                Add User
            </button>
        </div>

        <!-- Table -->
        <DataTable
            :value="users"
            paginator
            :rows="15"
            :rowsPerPageOptions="[10, 15, 25, 50, 100]"
            responsiveLayout="scroll"
            tableStyle="min-width: 50rem"
            :pt="{
                table: { class: 'text-sm' },
                thead: { class: 'bg-slate-50' },
                bodyRow: { class: 'hover:bg-slate-50 transition-colors duration-150 border-b border-slate-100' },
            }"
        >
            <template #empty>
                <div class="flex flex-col items-center justify-center py-16 text-slate-400">
                    <i class="pi pi-users text-4xl mb-3 opacity-30"></i>
                    <p class="text-sm font-medium">No users found</p>
                    <p class="text-xs mt-1">Click "Add User" to create the first account</p>
                </div>
            </template>

            <Column header="Name">
                <template #body="{ data }">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-linear-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-semibold shrink-0">
                            {{ data.firstname?.charAt(0)?.toUpperCase() ?? '?' }}
                        </div>
                        <div>
                            <p class="text-slate-800 text-sm font-medium leading-tight">
                                {{ [data.firstname, data.middlename, data.lastname].filter(Boolean).join(' ') }}
                                <span v-if="data.suffix" class="text-slate-400 font-normal">, {{ data.suffix }}</span>
                            </p>
                            <p class="text-slate-400 text-xs mt-0.5">{{ data.email }}</p>
                        </div>
                    </div>
                </template>
            </Column>

            <Column field="gender" header="Gender" class="w-28">
                <template #body="{ data }">
                    <span v-if="data.gender"
                        :class="[
                            'inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium border',
                            data.gender === 'Male'
                                ? 'bg-blue-50 text-blue-700 border-blue-100'
                                : 'bg-pink-50 text-pink-700 border-pink-100'
                        ]"
                    >
                        {{ data.gender }}
                    </span>
                    <span v-else class="text-slate-300 text-sm italic">—</span>
                </template>
            </Column>

            <Column field="date_of_birth" header="Date of Birth" class="w-36">
                <template #body="{ data }">
                    <span class="text-slate-500 text-sm">{{ data.date_of_birth ?? '—' }}</span>
                </template>
            </Column>

            <Column header="Actions" class="w-24">
                <template #body="{ data }">
                    <div class="flex items-center gap-1">
                        <button
                            type="button"
                            title="Edit user"
                            @click="view(data.pid)"
                            class="p-1.5 rounded-md text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-150 cursor-pointer"
                        >
                            <i class="pi pi-pencil"></i>
                        </button>
                        <button
                            type="button"
                            title="Delete user"
                            @click="archive(data.pid)"
                            class="p-1.5 rounded-md text-red-400 hover:bg-red-50 hover:text-red-600 transition-colors duration-150 cursor-pointer"
                        >
                            <i class="pi pi-trash"></i>
                        </button>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>

<script setup lang="ts">
import Password from 'primevue/password';
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { useUserStore } from '@/store/User';
import { User } from '@/interface/Interfaces';
import { useConfirmToast } from '@/composables/confirm';
import { useAppToast } from "@/composables/toast";

const { showConfirm } = useConfirmToast();
const toast = useAppToast();
const userStore = useUserStore();
const orderModal = ref<boolean>(false);
const users = computed<User[]>(() => userStore.users);
const user = computed<User>(() => userStore.user);
const genders = computed(() => userStore.genders);
const userInfo = reactive<User>({
    pid: '',
    email: '',
    firstname: '',
    middlename: '',
    lastname: '',
    suffix: '',
    gender: '',
    date_of_birth: new Date(),
    password: ''
});
const isUpdate = ref<boolean>(false);

watch(
    () => orderModal.value,
    (newVal) => {
        if (!newVal) {
            Object.assign(userInfo, {
                pid: '',
                email: '',
                firstname: '',
                middlename: '',
                lastname: '',
                suffix: '',
                gender: '',
                date_of_birth: new Date(),
                password: ''
            });
            isUpdate.value = false;
        }
    }
);

onMounted(async () => {
    await read();
});

const read = async () => {
    try {
        await userStore.read();
    } catch (err: any) {
        toast.error(err.response?.data?.message || "Failed to retrieve Users");
    }
};

const create = async () => {
    try {
        await userStore.create(userInfo);
        toast.success("User created successfully");
        orderModal.value = false;
    } catch (err: any) {
        toast.error(err.response?.data?.message || "Failed to create User");
    }
};

const view = async (pid: string) => {
    try {
        await userStore.view(pid);
        Object.assign(userInfo, user.value);
        isUpdate.value = true;
        orderModal.value = true;
    } catch (err: any) {
        toast.error(err.response?.data?.message || "Failed to retrieve User");
    }
};

const update = async () => {
    try {
        await userStore.update(userInfo);
        toast.success("User updated successfully");
        orderModal.value = false;
    } catch (err: any) {
        toast.error(err.response?.data?.message || "Failed to update User");
    }
};

const archive = async (pid: string) => {
    showConfirm({
        message: "Are you sure you want to delete this user?",
        header: "Delete Confirmation",
        onAccept: async () => {
            try {
                await userStore.archive(pid);
                toast.success("User deleted successfully");
            } catch (err: any) {
                toast.error(err.response?.data?.message || "Failed to delete User");
            }
        },
        onReject: () => {},
    });
};
</script>
