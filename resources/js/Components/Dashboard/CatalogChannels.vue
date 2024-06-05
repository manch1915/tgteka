<script setup>
import {watch} from "vue";
import {NSkeleton, useLoadingBar} from "naive-ui"
import CatalogChannelCard from "@/Components/Dashboard/CatalogChannelCard.vue";
import {useChannelStore} from "@/stores/ChannelStore.js";
import CustomPagination from "@/Components/Dashboard/CustomPagination.vue";
import { useWindowWidth } from '@/utilities/windowWidth.js'


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

const { windowWidth } = useWindowWidth()
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
        <CustomPagination @pagination-change-page="channelStore.fetchChannels" :data="channelStore.channels"/>
    </div>
</template>
