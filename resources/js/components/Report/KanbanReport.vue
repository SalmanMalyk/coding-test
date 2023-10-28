<template>
    <div class="grid md:grid-cols-1 gap-5">
        <TaskStatistics />

        <UserStatistics />
    </div>
</template>

<script setup>
import TaskStatistics from "./TaskStatistics.vue";
import UserStatistics from "./UserStatistics.vue";
</script>
