<script setup>
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";
import ChannelCard from "@/Components/Dashboard/ChannelCard.vue";
import axios from "axios";
import {onMounted, ref} from "vue";
import SortButton from "@/Components/Dashboard/SortButton.vue";
import SearchBar from "@/Components/Dashboard/SearchBar.vue";
import {useLoadingBar} from "naive-ui"

const channels = ref([])

const sortData = [
    'Рейтинг',
    'ER',
    'Просмотры',
    'Подписчики',
    'Цена',
    'CPМ'
]

const loading = useLoadingBar()

const getChannels = async (page = 1, search = '') => {
    loading.start()
    let url = route('channels.get') + `?page=${page}`;

    if(search.length > 0){
        url += `&search=${search}`;
    }

    await axios.get(url)
        .then(response => {
            loading.finish()
            channels.value = response.data
        })
}

const handleSearch = (search) => {
    getChannels(1, search);
}

onMounted(() => getChannels())
</script>

<template>
    <div class="w-full py-16">
        <search-bar @search="handleSearch"/>
    </div>
    <div class="flex gap-2 flex-wrap">
        <SortButton v-for="title in sortData" :title="title"/>
    </div>
    <div class="flex flex-col gap-y-4 mt-8">
        <template v-if="channels" v-for="(channel, index) in channels.data" :key="index">
            <ChannelCard :channel="channel"/>
        </template>
    </div>
    <div class="flex justify-center">
        <TailwindPagination @pagination-change-page="getChannels" :data="channels"  :limit="3" :active-classes="['bg-blue-950', 'rounded-full', 'shadow-inner', 'border', 'border-white', 'border-opacity-10', 'text-white', 'text-base', 'font-bold', 'font-[\'Open Sans\']', 'leading-tight']" :itemClasses="['border-none', 'text-violet-100', 'text-base', 'font-normal', 'font-[\'Inter\']', 'leading-normal',]" >
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
