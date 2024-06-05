<script setup>
import {ref} from 'vue';
import {useChannelStore} from "@/stores/ChannelStore.js";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiArrowDownDropCircleOutline, mdiArrowUpDropCircleOutline} from "@mdi/js";

const props = defineProps({
    title: String,
});
const channelStore = useChannelStore();

const isButtonDisabled = ref(false);

const sortChannels = (title) => {
    channelStore.sortChannels(title);
};

const setActiveButton = (title) => {
    if (!isButtonDisabled.value) {
        channelStore.activeButton = title;
        sortChannels(title);
        isButtonDisabled.value = true;
        setTimeout(() => {
            isButtonDisabled.value = false;
        }, 1000);
    }
};
</script>

<template>
    <button :class="{'background': channelStore.activeButton === title}" @click.prevent="setActiveButton(title)"
            :disabled="isButtonDisabled"
            class="transition px-5 py-2 lg:py-3 hover:bg-violet-950 rounded-full border border-violet-700 text-violet-100 lg:text-lg text-sm font-bold font-['Open Sans']">
        {{ title }}
        <span v-if="channelStore.activeButton === title" style="display: inline-block; vertical-align: middle;">
            <BaseIcon v-if="channelStore.sort === 'asc'" :path="mdiArrowUpDropCircleOutline"/>
            <BaseIcon v-else :path="mdiArrowDownDropCircleOutline"/>
        </span>
    </button>
</template>

<style>
.background {
    --tw-bg-opacity: 1;
    background-color: rgb(46 16 101 / var(--tw-bg-opacity));
}
</style>
