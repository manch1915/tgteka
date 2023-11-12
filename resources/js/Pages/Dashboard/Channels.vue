<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import UserChannels from "@/Components/Dashboard/UserChannels.vue";
import {router} from "@inertiajs/vue3";

</script>

<template>
    <AppLayout>
        <div class="flex items-center  pt-[80px] text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
            <h1 class="flex-grow text-center text-4xl leading-10">Мои каналы</h1>
            <button @click.prevent="router.visit(route('adding-channel'))" class="orders flex items-center gap-x-1.5">Добавить канал</button>
        </div>
        <UserChannels/>
    </AppLayout>
</template>

<style scoped lang="scss">
.orders {
    padding: 15px 25px;
    border-radius: 30px;
    background: rgb(135, 41, 255);
    transition: background-color 0.5s ease;
    &:hover{
    background: rgb(107, 33 ,168);
    }
}
</style>
