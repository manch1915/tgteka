<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import UserChannels from "@/Components/Dashboard/UserChannels.vue";
import {Head, router} from "@inertiajs/vue3";
import {ref} from "vue";

let emptyChannels = ref(false);

const handleEmptyChannels = (isEmpty) => {
    emptyChannels.value = isEmpty;
}

</script>

<template>
    <Head>
        <title>Мои каналы</title>
    </Head>
    <AppLayout>
        <div class="flex sm:flex-row gap-y-2 flex-col items-center pt-[80px] text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
            <div class="flex-1"></div>
            <h1 class="flex-1 text-center sm:text-lg md:text-xl lg:text-4xl text-lg lg:leading-10">Мои каналы</h1>
            <div class="flex-1 flex justify-end">
                <button v-if="!emptyChannels" @click.prevent="router.visit(route('adding-channel'))" class="orders flex items-center gap-x-1.5">Добавить канал</button>
            </div>
        </div>
        <div v-if="emptyChannels" class="flex mt-16 justify-center min-h-[20rem]">
            <div>
                <button @click.prevent="router.visit(route('adding-channel'))" class="pt-[80px] text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal orders flex items-center gap-x-1.5">Добавить канал</button>
            </div>
        </div>
        <UserChannels @isEmptyChannels="handleEmptyChannels"/>
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
