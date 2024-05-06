<script setup>
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";
import {onMounted, onUnmounted, ref, watch} from "vue";
import {NSkeleton, useLoadingBar} from "naive-ui"
import CatalogChannelCard from "@/Components/Dashboard/CatalogChannelCard.vue";
import {useChannelStore} from "@/stores/channelStore.js";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiArrowLeftBold, mdiArrowRightBold, mdiArrowRightBoldCircleOutline} from "@mdi/js";


const loading = useLoadingBar()

const channelStore = useChannelStore();
channelStore.fetchChannels()

watch(() => channelStore.loading, (isLoading) => {
    if (isLoading) {
        loading.start()
    } else {
        loading.finish()
    }
}, {immediate: true});
const changePage = () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
}

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
    <div class="flex flex-col gap-y-4 mt-8">
        <!-- Loading state -->
        <template v-if="channelStore.loading">
            <n-skeleton v-for="i in 4" height="210px" animated :theme-overrides="{
                color: '#301864',
                colorEnd: 'rgba(48,24,100,0.67)',
            }"/>
        </template>

        <!-- Results or no results -->
        <template v-else>
            <template v-if="channelStore.channels.data && channelStore.channels.data.length > 0"
                      v-for="(channel, index) in channelStore.channels.data" :key="index">

                <CatalogChannelCard :channel="channel"/>
            </template>
            <p v-else class="text-violet-100 text-center text-2xl font-bold font-['Open Sans'] leading-10">
                Подходящие каналы не найдены, измените параметры поиска.
            </p>
        </template>
    </div>
    <div class="flex justify-center mt-10">
        <TailwindPagination @pagination-change-page="channelStore.fetchChannels" @click.prevent="changePage" :data="channelStore.channels"
                            :limit="2"
                            :active-classes="['bg-blue-950', 'rounded-full', 'shadow-inner', 'border', 'border-white', 'border-opacity-10', 'text-white', 'text-base', 'font-bold', 'font-[\'Open Sans\']', 'leading-tight']"
                            :itemClasses="['border-none', 'text-violet-100', 'text-base', 'font-normal', 'font-[\'Inter\']', 'leading-normal',]">
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
