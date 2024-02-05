<script setup>
import { useChannelStore } from "@/stores/channelStore.js";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiArrowDownDropCircleOutline, mdiArrowUpDropCircleOutline} from "@mdi/js";

const props = defineProps({
  title: String,
})
const channelStore = useChannelStore();

const sortChannels = (title) => {
    channelStore.sortChannels(title)
};
const setActiveButton = (title) => {
    channelStore.activeButton = title;
    sortChannels(title);
};
</script>

<template>
  <button :class="{'background': channelStore.activeButton === title}" @click.prevent="setActiveButton(title)" class="transition px-5 py-3 hover:bg-violet-950 rounded-full border border-violet-700 text-violet-100 text-lg font-bold font-['Open Sans']">
    {{title}}
    <span v-if="channelStore.activeButton === title">
        <BaseIcon v-if="channelStore.sort === 'asc'" :path="mdiArrowUpDropCircleOutline"/>
        <BaseIcon v-else :path="mdiArrowDownDropCircleOutline"/>
    </span>
  </button>
</template>

<style>
.background{
    --tw-bg-opacity: 1;
    background-color: rgb(46 16 101 / var(--tw-bg-opacity));
}
</style>
