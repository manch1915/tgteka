<script setup>
import { useProfileButtons } from "@/stores/profileButtons.js";
import { onBeforeMount, ref, watch } from "vue";
import {Link, usePage} from '@inertiajs/vue3'

const profileButton = useProfileButtons();
const page = usePage();
const profileButtonTitle = ref(profileButton.activeButton);
const props = defineProps({
    title: String,
    routeHref: String,
    icon: String
});

watch(
    () => profileButton.activeButton,
    (newButton) => {
        profileButtonTitle.value = newButton;
    }
);
onBeforeMount(() => {
    profileButton.setActiveButtonByUrl(page.url);
});
</script>

<template>
    <Link :href="routeHref" preserve-scroll>
        <div @click="profileButton.setActiveButton(title)" class="button flex justify-between items-center" :class="profileButtonTitle === title ? 'background' : ''">
            <div class="flex gap-x-2.5 items-center">
                <img class="select-none" :src="icon" alt="">
                <button
                    :class="[
                    'text-left',
                    'transition',
                ]"
                    class="2xl:text-lg leading-4 lg:text-[0.9rem] text-sm"
                >
                    {{ title }}
                </button>
            </div>
            <img src="/images/arrow-down.svg" alt="arrow" class="rotate-[-90deg]">
        </div>
    </Link>
</template>

<style scoped lang="scss">
.button {
    width: 100%;
    border-radius: 4px;
    padding: 10px;
    color: #EAE0FF;
    font-family: Open Sans,serif;
    font-size: 18px;
    font-weight: 700;
    line-height: 130%;

    &:hover {
        background: #6522D9;
    }
    &.background {
        background: #6522D9;
    }
    @media screen and (max-width: 1024px){
        color: #EAE0FF;
        font-family: Open Sans,sans-serif;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: 130%;
        &.background {
            background: rgba(234, 224, 255, 0.2);
        }
    }
}
</style>
