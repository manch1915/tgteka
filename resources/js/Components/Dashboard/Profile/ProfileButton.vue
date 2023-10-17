<script setup>
import { useProfileButtons } from "@/stores/profileButtons.js";
import { ref, watch } from 'vue';
import {Link} from '@inertiajs/vue3'

const profileButton = useProfileButtons();
const profileButtonTitle = ref(profileButton.activeButton);
const props = defineProps({
    title: String,
    routeHref: String
});

watch(
    () => profileButton.activeButton,
    (newButton) => {
        profileButtonTitle.value = newButton;
    }
);
</script>

<template>
    <Link :href="routeHref">
    <button
        @click="profileButton.setActiveButton(title)"
        :class="[
            'text-left',
            'transition',
            profileButtonTitle === title ? 'background' : ''
        ]"
    >
        {{ title }}
    </button>
    </Link>
</template>

<style scoped lang="scss">
button {
    width: 100%;
    border-radius: 4px;
    padding: 10px;
    color: var(--White, #EAE0FF);
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
}
</style>
