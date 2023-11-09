<script setup>
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";
import ChannelCard from "@/Components/Dashboard/ChannelCard.vue";
import axios from "axios";
import {onMounted, ref} from "vue";

const channels = ref([])

const getPatterns = async (page = 1) => {
    const url = route('channels.get') + `?page=${page}`
    await axios.get(url)
        .then(response => {
            channels.value = response.data
        })
}
onMounted(() => getPatterns())
</script>

<template>
    <div class="flex flex-col gap-y-4 mt-8">
        <template v-if="channels" v-for="(channel, index) in channels.data" :key="index">
            <ChannelCard :title="channel.channel_name" :description="channel.description"/>
        </template>
    </div>
    <div class="flex justify-center">
        <TailwindPagination @pagination-change-page="getPatterns" :data="channels"  :limit="3" :active-classes="['bg-blue-950', 'rounded-full', 'shadow-inner', 'border', 'border-white', 'border-opacity-10', 'text-white', 'text-base', 'font-bold', 'font-[\'Open Sans\']', 'leading-tight']" :itemClasses="['border-none', 'text-violet-100', 'text-base', 'font-normal', 'font-[\'Inter\']', 'leading-normal',]" >
            <template v-slot:prev-nav>
                <p class="text-center text-violet-100 text-base font-normal font-['Inter'] leading-normal">Назад</p>
            </template>
            <template v-slot:next-nav>
                <p class="text-center text-violet-100 text-base font-semibold font-['Inter'] leading-snug">Вперёд</p>
            </template>
        </TailwindPagination>
    </div>
</template>

<style scoped lang="scss">

</style>
