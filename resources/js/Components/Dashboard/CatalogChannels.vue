<script setup>
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";
import axios from "axios";
import {inject, ref, watch} from "vue";
import {NSkeleton, useLoadingBar} from "naive-ui"
import CatalogChannelCard from "@/Components/Dashboard/CatalogChannelCard.vue";
import { useChannelStore } from "@/stores/channelStore.js";


const loading = useLoadingBar()

const channelStore = useChannelStore();
channelStore.fetchChannels()

watch(() => channelStore.loading, (isLoading) => {
    if (isLoading) {
        loading.start()
    } else {
        loading.finish()
    }
}, { immediate: true });

</script>
<template>
  <div class="flex flex-col gap-y-4 mt-8">
    <template v-if="channelStore.channels.data" v-for="(channel, index) in channelStore.channels.data" :key="index">
      <CatalogChannelCard :channel="channel"/>
    </template>
    <template v-else>
      <n-skeleton v-for="i in 4" height="210px" animated :theme-overrides="{
                color: '#301864',
                colorEnd: 'rgba(48,24,100,0.67)',
            }"/>
    </template>
  </div>
  <div class="flex justify-center">
    <TailwindPagination @pagination-change-page="channelStore.fetchChannels" :data="channelStore.channels" :limit="3"
                        :active-classes="['bg-blue-950', 'rounded-full', 'shadow-inner', 'border', 'border-white', 'border-opacity-10', 'text-white', 'text-base', 'font-bold', 'font-[\'Open Sans\']', 'leading-tight']"
                        :itemClasses="['border-none', 'text-violet-100', 'text-base', 'font-normal', 'font-[\'Inter\']', 'leading-normal',]">
      <template v-slot:prev-nav>
        <p class="text-center text-violet-100 text-base font-normal font-['Inter'] leading-normal">Назад</p>
      </template>
      <template v-slot:next-nav>
        <p class="text-center text-violet-100 text-base font-semibold font-['Inter'] leading-snug">Вперёд</p>
      </template>
    </TailwindPagination>
  </div>
</template>
