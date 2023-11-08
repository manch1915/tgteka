<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import SortButton from "@/Components/Dashboard/SortButton.vue";
import UserChanels from "@/Components/Dashboard/UserChanels.vue";
import {router} from "@inertiajs/vue3";
const sortData = [
    'Рейтинг',
    'ER',
    'Просмотры',
    'Подписчики',
    'Цена',
    'CPМ'
]

</script>

<template>
    <AppLayout>
        <div class="flex items-center  pt-[80px] text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
            <h1 class="flex-grow text-center text-4xl leading-10">Мои каналы</h1>
            <button @click.prevent="router.visit(route('adding-channel'))" class="orders flex items-center gap-x-1.5">Добавить канал</button>
        </div>
        <div class="w-full flex relative py-16">
            <TextInput class="w-full"/>
            <img class="absolute search-channel-button" src="/images/search.svg" alt="search">
        </div>
        <div class="flex gap-x-2">
            <SortButton v-for="title in sortData" :title="title"/>
        </div>
        <UserChanels/>
    </AppLayout>
</template>

<style scoped lang="scss">
.search-channel-button {
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    border-left: 1px solid #EAE0FF;
    padding-left: 10px;
}
.orders {
    padding: 15px 25px;
    border-radius: 30px;
    background: rgb(135, 41, 255);
}
</style>
