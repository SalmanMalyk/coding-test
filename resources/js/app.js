import "./bootstrap";
import { createApp } from "vue";
import { createPinia } from "pinia";

import Alpine from "alpinejs";
import TaskCard from "./components/TaskCard.vue";
import TaskColumn from "./components/TaskColumn.vue";
import KanbanBoard from "./components/KanbanBoard.vue";
import GenericModal from "./components/modals/GenericModal.vue";
import KanbanReport from "./components/Report/KanbanReport.vue";
import TrashBin from "./components/TrashBin.vue";

const pinia = createPinia();
const app = createApp({});
app.use(pinia);

app.component("TaskCard", TaskCard)
    .component("TaskColumn", TaskColumn)
    .component("KanbanBoard", KanbanBoard)
    .component("GenericModal", GenericModal)
    .component("KanbanReport", KanbanReport)
    .component("TrashBin", TrashBin);

app.mount("#app");

window.Alpine = Alpine;

Alpine.start();
