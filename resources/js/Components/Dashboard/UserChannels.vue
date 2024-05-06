<script setup>
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";
import ChannelCard from "@/Components/Dashboard/ChannelCard.vue";
import axios from "axios";
import {onMounted, ref, defineEmits} from "vue";
import SortButton from "@/Components/Dashboard/SortButton.vue";
import SearchBar from "@/Components/Dashboard/SearchBar.vue";
import {useLoadingBar} from "naive-ui"
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiArrowLeftBold, mdiArrowRightBold} from "@mdi/js";

const channels = ref({})

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
            console.log(response)
            channels.value = response.data
        })
}

const handleSearch = (search) => {
    getChannels(1, search);
}
const emit = defineEmits(['isEmptyChannels']);
onMounted(async () => {
    try {
        await getChannels();
        if (channels.value.data.length === 0){
            emit('isEmptyChannels', true);
        } else {
            emit('isEmptyChannels', false);
        }
    } catch (error) {
        console.error("Error fetching channels: ", error);
    }
});
const windowWidth = ref(window.innerWidth);
const updateWidth = () => {
    windowWidth.value = window.innerWidth;
};

onMounted(() => {
    // Add the updateWidth function as a window resize listener
    window.addEventListener("resize", updateWidth);
});

onUnmounted(() => {
    // Remove listener when component is unmounted
    window.removeEventListener("resize", updateWidth);
});
</script>

<template>
    <div v-if="channels.data && channels.data.length" class="w-full sm:py-16 py-8 px-2">
        <search-bar @search="handleSearch"/>
    </div>
    <div v-if="channels.data && channels.data.length" class="mt-4 flex flex-wrap lg:gap-3 gap-2 filter_buttons lg:justify-normal justify-center">
        <SortButton v-for="title in sortData" :title="title"/>
    </div>
    <div v-if="channels.data && channels.data.length" class="flex flex-col gap-y-4 mt-8 px-2">
        <template v-if="channels" v-for="(channel, index) in channels.data" :key="index">
            <ChannelCard :channel="channel"/>
        </template>
    </div>
    <div v-if="channels.data && channels.data.length" class="flex justify-center">
        <TailwindPagination @pagination-change-page="getChannels" :data="channels"  :limit="2" :active-classes="['bg-blue-950', 'rounded-full', 'shadow-inner', 'border', 'border-white', 'border-opacity-10', 'text-white', 'text-base', 'font-bold', 'font-[\'Open Sans\']', 'leading-tight']" :itemClasses="['border-none', 'text-violet-100', 'text-base', 'font-normal', 'font-[\'Inter\']', 'leading-normal',]" >
            <template v-slot:prev-nav>
                <p v-if="windowWidth >= 640" class="text-center text-violet-100 text-base font-normal font-['Inter'] leading-normal">Назад</p>
                <BaseIcon v-else size="15" :path="mdiArrowLeftBold"/>
            </template>
            <template v-slot:next-nav>
                <p  v-if="windowWidth >= 640" class="text-center text-violet-100 text-base font-semibold font-['Inter'] leading-snug">Вперёд</p>
                <BaseIcon v-else size="15" :path="mdiArrowRightBold"/>
            </template>
        </TailwindPagination>
    </div>
</template>

<style scoped lang="scss">

</style>
