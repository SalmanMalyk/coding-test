<template>
    <div
        class="w-[300px] bg-sky-950 rounded-lg shadow-lg overflow-y-auto max-h-screen"
    >
        <div class="p-4">
            <div
                class="flex items-center justify-between sticky top-0 z-10 bg-sky-950 py-2"
            >
                <span
                    class="bg-gray-100 text-black h-6 w-6 rounded-full flex items-center justify-center font-bold mb-2"
                    v-text="kanban.phases[props.phase_id]?.tasks_count"
                ></span>

                <h2 class="text-lg text-zinc-100 font-black mb-2">
                    {{ kanban.phases[props.phase_id]?.name }}
                </h2>
                <TaskColumnMenu :phase="kanban.phases[props.phase_id]" />
            </div>
            <task-card
                v-if="kanban.phases[props.phase_id].tasks.length > 0"
                v-for="(task, index) in kanban.phases[props.phase_id].tasks"
                :task="task"
                :key="index"
            />

            <!-- A card to create a new task -->
            <div
                class="w-full flex items-center justify-between bg-white text-gray-900 hover:cursor-pointer shadow-md rounded-lg p-3 relative"
                @click="createTask()"
                v-if="props.phase_id != 6"
            >
                <span>Create a new task</span>
                <PlusIcon class="h-6 w-6" aria-hidden="true" />
            </div>
        </div>
    </div>
</template>

<script setup>
// get the props
import { useKanbanStore } from "../stores/kanban";
import { PlusIcon } from "@heroicons/vue/20/solid";
import TaskColumnMenu from "./TaskColumnMenu.vue";

const kanban = useKanbanStore();

const props = defineProps({
    phase_id: {
        type: Number,
        required: true,
    },
});

const createTask = function () {
    kanban.creatingTask = true;
    kanban.creatingTaskProps.phase_id = props.phase_id;
};
</script>
