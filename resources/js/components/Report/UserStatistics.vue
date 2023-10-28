<template>
    <div class="bg-gray-800 px-8 py-10 rounded-xl shadow-lg">
        <h1 class="text-white font-bold text-xl mb-5">Assigned Tasks:</h1>
        <ul role="list" class="divide-y divide-gray-800">
            <template v-if="users.length">
                <li
                    class="flex justify-between gap-x-6 py-5"
                    v-for="(user, index) in users"
                    :key="index"
                >
                    <div class="flex min-w-0 gap-x-4">
                        <img
                            class="h-12 w-12 flex-none rounded-full bg-gray-800"
                            :src="user.profile_picture_url"
                            :alt="user.name"
                        />
                        <div class="min-w-0 flex-auto">
                            <p
                                class="text-sm font-semibold leading-6 text-white"
                                v-text="user.name"
                            ></p>
                            <p
                                class="mt-1 truncate text-xs leading-5 text-gray-400"
                                v-text="user.email"
                            ></p>
                        </div>
                    </div>
                    <div
                        class="hidden shrink-0 sm:flex sm:flex-col sm:items-end"
                    >
                        <span
                            class="bg-gray-100 text-black h-10 w-10 rounded-full flex items-center justify-center text-2xl font-semibold tracking-tight order-first"
                            v-text="user.tasks_count"
                        >
                        </span>
                    </div>
                </li>
            </template>

            <li
                class="flex justify-start items-center gap-x-2 py-5 text-white"
                v-else
            >
                <ExclamationCircleIcon class="h-5 w-5 text-yellow-500" />
                No users found.
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ExclamationCircleIcon } from "@heroicons/vue/20/solid";
import { onMounted, ref } from "vue";

const users = ref([]);

const fetchUsers = async () => {
    try {
        const response = await fetch("/api/users");
        const data = await response.json();
        users.value = data;
    } catch (error) {
        console.error("Something went wrong", error);
    }
};

onMounted(() => {
    fetchUsers();
});
</script>
