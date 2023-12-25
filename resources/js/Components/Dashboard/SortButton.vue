<script setup>
import { useChannelStore } from "@/stores/channelStore.js";

const props = defineProps({
  title: String,
})

const channelStore = useChannelStore();

const sortChannels = (title) => {
    channelStore.sortChannels(title)
};
</script>

<template>
  <button @click.prevent="sortChannels(title)" class="transition px-5 py-3 hover:bg-violet-950 rounded-full border border-violet-700 justify-start items-start text-violet-100 text-lg font-bold font-['Open Sans']">
    {{title}}
  </button>
</template>
