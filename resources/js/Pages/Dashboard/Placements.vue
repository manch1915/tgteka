<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";
import {onMounted, ref, watch} from "vue";
import PlacementCard from "@/Components/Dashboard/PlacementCard.vue";
import {darkTheme, dateRuRU, NConfigProvider, NDatePicker, NSlider, ruRU, useLoadingBar} from "naive-ui";

const channels = ref([])
const activeSortButton = ref('')
const value = ref([0, 1500])
const range = ref([118313526e4, Date.now()])

const loading = useLoadingBar()

const SORT_DATA = [
    {
        title: 'в ожидании',
        value: 'pending'
    },
    {
        title: 'принятый',
        value: 'accepted'
    },
    {
        title: 'отклоненный',
        value: 'declined'
    },
    {
        title: 'проверить',
        value: 'check'
    },
    {
        title: 'законченный',
        value: 'finished'
    }
]

const formatTooltip = (value) => `${value} руб.`

let debounceTimeoutId
const getPlacements = async (page = 1) => {

    clearTimeout(debounceTimeoutId);

    debounceTimeoutId = setTimeout( async () => {
        loading.start()
        let url = route('placements.get') +
            `?page=${page}` +
            `&status=${activeSortButton.value}` +
            `&minPrice=${value.value[0]}` +
            `&startDate=${new Date(range.value[0]).toJSON()}` +
            `&endDate=${new Date(range.value[1]).toJSON()}`;

        await axios.get(url)
            .then(response => channels.value = response.data)
            .catch(err => console.log(err))
            .finally(() => loading.finish());

    }, 500);

};
onMounted(() => getPlacements())

watch([activeSortButton, value, range], () => {
    getPlacements()
})
</script>

<template>
    <AppLayout>
        <div class="py-20 text-center">
            <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Мои размещения</h1>
            <div class="flex justify-between items-center gap-x-8 mt-4">
                <div class="flex gap-x-2">
                <template v-for="sort in SORT_DATA">
                    <button :class="{'background': activeSortButton === sort.title}" @click.prevent="activeSortButton = sort.value" class="transition px-5 py-1 hover:bg-violet-950 rounded-full border border-violet-700 justify-start items-start text-violet-100 text-sm font-bold font-['Open Sans']">
                        {{sort.title}}
                    </button>
                </template>
                </div>
                <div class="w-full">
                    <n-slider v-model:value="value" range :step="1" :max="50000" :format-tooltip="formatTooltip"/>
                    <div class="flex justify-between">
                        <p class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-10">{{value[0]}} руб.</p>
                        <p class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-10">-</p>
                        <p class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-10">{{value[1]}} руб.</p>
                    </div>
                    <n-config-provider :locale="ruRU" :date-locale="dateRuRU" :theme="darkTheme">
                        <n-date-picker v-model:value="range" type="daterange" clearable />
                    </n-config-provider>
                </div>
            </div>
            <div class="flex flex-col gap-y-4 mt-8">
                <template v-if="channels" v-for="(order, index) in channels.data" :key="index">
                    <placement-card :order="order"/>
                </template>
            </div>
            <div class="flex justify-center">
                <TailwindPagination @pagination-change-page="getPlacements" :data="channels"  :limit="3" :active-classes="['bg-blue-950', 'rounded-full', 'shadow-inner', 'border', 'border-white', 'border-opacity-10', 'text-white', 'text-base', 'font-bold', 'font-[\'Open Sans\']', 'leading-tight']" :itemClasses="['border-none', 'text-violet-100', 'text-base', 'font-normal', 'font-[\'Inter\']', 'leading-normal',]" >
                    <template v-slot:prev-nav>
                        <p class="text-center text-violet-100 text-base font-normal font-['Inter'] leading-normal">Назад</p>
                    </template>
                    <template v-slot:next-nav>
                        <p class="text-center text-violet-100 text-base font-semibold font-['Inter'] leading-snug">Вперёд</p>
                    </template>
                </TailwindPagination>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">
.background{
    --tw-bg-opacity: 1;
    background-color: rgb(46 16 101 / var(--tw-bg-opacity));
}
</style>
