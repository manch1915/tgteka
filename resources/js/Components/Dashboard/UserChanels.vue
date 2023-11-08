<script setup>
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";
import ChannelCard from "@/Components/Dashboard/ChannelCard.vue";

</script>

<template>
    <div class="flex flex-col gap-y-4 mt-8">
        <ChannelCard/>
        <ChannelCard/>
        <ChannelCard/>
        <ChannelCard/>
        <ChannelCard/>
    </div>
<!--    <TailwindPagination :limit="3" :active-classes="['bg-blue-950', 'rounded-full', 'shadow-inner', 'border', 'border-white', 'border-opacity-10', 'text-white', 'text-base', 'font-bold', 'font-[\'Open Sans\']', 'leading-tight']" :itemClasses="['border-none', 'text-violet-100', 'text-base', 'font-normal', 'font-[\'Inter\']', 'leading-normal',]" @pagination-change-page="getPatterns" :data="patterns" >
        <template v-slot:prev-nav>
            <p class="text-center text-violet-100 text-base font-normal font-['Inter'] leading-normal">Назад</p>
        </template>
        <template v-slot:next-nav>
            <p class="text-center text-violet-100 text-base font-semibold font-['Inter'] leading-snug">Вперёд</p>
        </template>
    </TailwindPagination>-->

</template>

<style scoped lang="scss">

</style>
