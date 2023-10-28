<template>
    <div class="flex justify-end">
        <button
            @click.prevent="show = true"
            type="button"
            class="rounded-md flex items-center bg-red-700 px-2.5 py-1.5 font-semibold text-white shadow-sm hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500"
        >
            <TrashIcon class="h-5 w-5 mr-1" /> Trash Bin
        </button>
    </div>

    <Teleport to="body">
        <generic-modal :show="show" @close="show = false" key="showTrashModal">
            <DialogTitle
                as="h3"
                class="text-base font-semibold leading-6 text-gray-900 flex items-start"
            >
                View Trash Item
            </DialogTitle>

            <div class="mt-2">
                <ul role="list" class="divide-y divide-gray-100">
                    <li
                        class="flex justify-start items-center gap-x-2 py-5 text-gray-900"
                        v-if="
                            !items?.deletedPhases?.length &&
                            !items?.deletedTasks?.length
                        "
                    >
                        <ExclamationCircleIcon
                            class="h-5 w-5 text-yellow-500"
                        />
                        Nothing here :)
                    </li>

                    <template v-if="items?.deletedPhases?.length">
                        <li
                            class="flex items-center justify-between gap-x-6 py-5"
                            v-for="(item, index) in items.deletedPhases"
                            :key="index"
                        >
                            <div class="min-w-0">
                                <div class="flex items-start gap-x-3">
                                    <p
                                        class="text-sm font-semibold leading-6 text-gray-900"
                                        v-text="item.name"
                                    ></p>
                                    <p
                                        class="rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset text-gray-100 bg-gray-500"
                                    >
                                        Phase
                                        <span
                                            title="Number of deleted tasks"
                                            class="h-3.5 w-3.5 inline-flex rounded-full items-center justify-center bg-white text-gray-800 text-xs font-bold"
                                            v-text="item.deleted_tasks"
                                        ></span>
                                    </p>
                                </div>
                                <div
                                    class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500"
                                >
                                    <p class="whitespace-nowrap">
                                        Deleted on
                                        <time
                                            :datetime="item.deleted_at"
                                            v-text="
                                                moment(item.deleted_at).format(
                                                    'MMM DD, YYYY hh:mm A'
                                                )
                                            "
                                        ></time>
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-none items-center gap-x-4">
                                <button
                                    @click.prevent="
                                        restoreTask(item.id, 'phase')
                                    "
                                    type="button"
                                    class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-100"
                                >
                                    Restore
                                </button>
                            </div>
                        </li>
                    </template>

                    <template v-if="items?.deletedTasks?.length">
                        <li
                            class="flex items-center justify-between gap-x-6 py-5"
                            v-for="(item, index) in items.deletedTasks"
                            :key="index"
                        >
                            <div class="min-w-0">
                                <div class="flex items-start gap-x-2">
                                    <p
                                        class="text-sm font-semibold leading-6 text-gray-900"
                                        v-text="item.name"
                                    ></p>
                                    <p
                                        class="rounded-md whitespace-nowrap mt-0.5 px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset text-gray-100 bg-gray-500"
                                        v-text="item.phase?.name"
                                    ></p>
                                </div>
                                <div
                                    class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500"
                                >
                                    <p class="whitespace-nowrap">
                                        Deleted on
                                        <time
                                            :datetime="item.deleted_at"
                                            v-text="
                                                moment(item.deleted_at).format(
                                                    'MMM DD, YYYY hh:mm A'
                                                )
                                            "
                                        ></time>
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-none items-center gap-x-4">
                                <button
                                    @click.prevent="
                                        restoreTask(item.id, 'task')
                                    "
                                    type="button"
                                    class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-green-100"
                                >
                                    Restore
                                </button>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>
        </generic-modal>
    </Teleport>
</template>

<script setup>
import moment from "moment";
import { ref, watch } from "vue";
import { DialogTitle } from "@headlessui/vue";
import { ExclamationCircleIcon, TrashIcon } from "@heroicons/vue/20/solid";
import { useKanbanStore } from "../stores/kanban";

const show = ref(false);
const items = ref([]);

const kanban = useKanbanStore();

const fetchTrash = async () => {
    try {
        const { data } = await axios.get(`/api/trash`);
        items.value = data;
    } catch (error) {
        console.log(error);
    }
};

const restoreTask = async (id, type) => {
    try {
        const { data } = await axios.post(`/api/trash/${id}/restore`, {
            type,
        });
        await kanban.refreshTasks();
        show.value = false;
    } catch (error) {
        console.log(error);
    }
};

watch(show, (value) => {
    if (value) {
        fetchTrash();
    }
});
</script>
