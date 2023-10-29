<template>
    <div class="relative">
        <Menu as="div" class="relative text-left">
            <MenuButton
                class="focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
            >
                <EllipsisVerticalIcon
                    class="h-6 w-6 text-white hover:cursor-pointer"
                    aria-hidden="true"
                />
            </MenuButton>

            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <MenuItems
                    class="absolute right-0 mt-2 w-56 z-50 origin-top-right divide-y divide-zinc-800 rounded-md bg-zinc-500 shadow-lg ring-1 ring-zinc-500 focus:outline-none"
                >
                    <div class="px-1 py-1">
                        <MenuItem v-slot="{ active }">
                            <button
                                :class="[
                                    active
                                        ? 'bg-white text-black'
                                        : 'text-white',
                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                                @click="createTask"
                            >
                                <PlusIcon
                                    class="mr-3 h-5 w-5 text-gray-100 group-hover:text-gray-500"
                                    aria-hidden="true"
                                />
                                Create Task
                            </button>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                            <button
                                :class="[
                                    active
                                        ? 'bg-white text-black'
                                        : 'text-white',
                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                                @click="toggleCompletionPhase"
                            >
                                <XMarkIcon
                                    class="mr-3 h-5 w-5 text-gray-100 group-hover:text-gray-500"
                                    aria-hidden="true"
                                    v-if="props.phase.is_completion_phase"
                                />
                                <CheckIcon
                                    class="mr-3 h-5 w-5 text-gray-100 group-hover:text-gray-500"
                                    aria-hidden="true"
                                    v-else
                                />
                                {{
                                    props.phase.is_completion_phase
                                        ? "Un-mark"
                                        : "Mark"
                                }}
                                as Completion
                            </button>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                            <button
                                :class="[
                                    active
                                        ? 'bg-white text-black'
                                        : 'text-white',
                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]"
                                @click="deleteColumn"
                            >
                                <TrashIcon
                                    class="mr-3 h-5 w-5 text-gray-100 group-hover:text-gray-500"
                                    aria-hidden="true"
                                />
                                Delete Column
                            </button>
                        </MenuItem>
                    </div>
                </MenuItems>
            </transition>
        </Menu>
    </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";
import {
    CheckIcon,
    EllipsisVerticalIcon,
    PlusIcon,
    TrashIcon,
    XMarkIcon,
} from "@heroicons/vue/20/solid";

import { useKanbanStore } from "../stores/kanban";

const kanban = useKanbanStore();

const props = defineProps({
    phase: {
        type: Object,
        required: true,
    },
});

const toggleCompletionPhase = async () => {
    try {
        await axios.put(`/api/phases/${props.phase.id}`);
        kanban.refreshTasks();
    } catch (error) {
        console.error("There was an error fetching the tasks!", error);
    }
};

const createTask = function () {
    kanban.creatingTask = true;
    kanban.creatingTaskProps.phase_id = props.phase_id;
};

const deleteColumn = async () => {
    if (
        confirm(
            "Are you sure you want to delete this column? This will also delete all the tasks."
        )
    ) {
        try {
            const response = await axios.delete(
                "/api/phases/" + props.phase.id
            );
            kanban.refreshTasks();
        } catch (error) {
            console.error("There was an error fetching the tasks!", error);
        }
    }
};
</script>
