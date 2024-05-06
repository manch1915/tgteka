<template>
    <TailwindPagination
        @pagination-change-page="emitPaginationChangePage"
        @click.prevent="changePage"
        :data="data"
        :limit="limit"
        :active-classes="activeClasses"
        :item-classes="itemClasses"
    >
        <template v-slot:prev-nav>
            <p v-if="windowWidth >= 640" class="text-center text-violet-100 text-base font-normal font-['Inter'] leading-normal">Назад</p>
            <BaseIcon v-else size="15" :path="mdiArrowLeftBold"/>
        </template>
        <template v-slot:next-nav>
            <p v-if="windowWidth >= 640" class="text-center text-violet-100 text-base font-semibold font-['Inter'] leading-snug">Вперёд</p>
            <BaseIcon v-else size="15" :path="mdiArrowRightBold"/>
        </template>
    </TailwindPagination>
</template>

<script setup>
import {ref, onMounted, defineEmits, onUnmounted, computed} from 'vue';
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiArrowLeftBold, mdiArrowRightBold} from "@mdi/js";
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";

// Import TailwindPagination, BaseIcon, and other necessary components and variables

const props = defineProps({
    data: Object
})

const activeClasses = ['bg-blue-950', 'rounded-full', 'shadow-inner', 'border', 'border-white', 'border-opacity-10', 'text-white', 'text-base', 'font-bold', 'font-[\'Open Sans\']', 'leading-tight'];
const itemClasses = ['border-none', 'text-violet-100', 'text-base', 'font-normal', 'font-[\'Inter\']', 'leading-normal'];
const windowWidth = ref(window.innerWidth);

const emit = defineEmits(['pagination-change-page']);
// Emit the pagination-change-page event whenever the pagination changes
const emitPaginationChangePage = (page) => {
    emit('pagination-change-page', page);
};

const limit = computed(() => {
    return windowWidth.value < 425 ? 1 : 3;
});

// Handle changePage event
const changePage = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
const updateWidth = () => {
    windowWidth.value = window.innerWidth;
};
// Listen to window width changes
onMounted(() => {
    window.addEventListener('resize', () => {
        windowWidth.value = window.innerWidth;
    });
});
onUnmounted(() => {
    // Remove listener when component is unmounted
    window.removeEventListener("resize", updateWidth);
});
</script>
