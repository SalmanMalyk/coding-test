<template>
    <div class="bg-gray-800 py-12 sm:py-14 rounded-xl shadow-lg">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <dl
                class="grid grid-cols-1 gap-x-8 gap-y-16 text-center lg:grid-cols-3"
            >
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-base leading-7 text-gray-400">
                        Weekly completes tasks
                    </dt>
                    <dd
                        class="order-first text-3xl font-semibold tracking-tight text-white sm:text-5xl"
                        v-text="stats.weeklyTasks || 0"
                    ></dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-base leading-7 text-gray-400">
                        Monthly completes tasks
                    </dt>
                    <dd
                        class="order-first text-3xl font-semibold tracking-tight text-white sm:text-5xl"
                        v-text="stats.monthlyTasks || 0"
                    ></dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-base leading-7 text-gray-400">
                        Total Tasks
                    </dt>
                    <dd
                        class="order-first text-3xl font-semibold tracking-tight text-white sm:text-5xl"
                        v-text="stats.totalTasks || 0"
                    ></dd>
                </div>
            </dl>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";

const stats = ref([]);

const fetchStats = async () => {
    try {
        const { data } = await axios.get("/api/stats");
        stats.value = data;
    } catch (error) {
        console.log(error);
    }
};

onMounted(() => {
    fetchStats();
});
</script>
