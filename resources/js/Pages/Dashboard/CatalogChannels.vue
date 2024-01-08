<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import SortButton from '@/Components/Dashboard/SortButton.vue'
import { closeModal } from 'jenesius-vue-modal'
import SearchBar from '@/Components/Dashboard/SearchBar.vue'
import {
    NCheckbox,
    NInput,
    NSelect,
    NSlider
} from 'naive-ui'
import {
    checkboxThemeOverrides,
    inputThemeOverrides,
    selectThemeOverrides,
    sliderThemeOverrides
} from '@/themeOverrides.js'
import { ref, provide, computed } from 'vue'
import CatalogChannels from '@/Components/Dashboard/CatalogChannels.vue'
import { useMainStore } from '@/stores/main.js'
import { useChannelStore } from "@/stores/channelStore.js";

const SORT_DATA = ['Рейтинг', 'ER', 'Просмотры', 'Подписчики', 'Цена', 'CPМ']

const props = defineProps({
    count: Number,
})

closeModal()

const store = useMainStore()
store.fetchTopics()

const channelSubjects = computed(() =>
    store.topics.map((topic) => ({
        label: topic.title,
        value: topic.id
    }))
)

const formatTooltip = (value) => `${value}+`
const formatTooltipPercent = (value) => `> ${value}%`
const additionalFilter = ref(false)
const placeholder = ['от', 'до']

const channelStore = useChannelStore();

let timeout
const searchHandler = (value) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
        channelStore.searchData = value
        channelStore.fetchChannels()
    }, 500)
}

</script>

<template>
    <AppLayout>
        <div class="py-20 text-center">
            <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Каталог Telegram-каналов</h1>
            <p class="sm:hidden text-center block total pt-4 text-violet-100 text-sm font-normal font-['Open Sans'] leading-normal">Всего каналов {{count}}</p>
        </div>
        <div class="grid catalog">
            <div class="w-full pr-0 filter sm:pr-10">
                <div class="sm:block flex justify-between gap-x-12">
                    <div class="flex items-center justify-start pb-[24px] gap-x-2.5 sm:justify-between sm:gap-x-0">
                        <h1 class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">Фильтр</h1>
                        <img src="/images/setting.svg" alt="setting">
                    </div>
                    <div class="inline w-full sm:hidden">
                        <n-select :theme-overrides="selectThemeOverrides"/>
                    </div>
                </div>
                <div class="pb-6">
                    <SearchBar @search="searchHandler" class="py-2"/>
                    <div class="flex items-center gap-x-2">
                        <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                        <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Искать также в описании</p>
                    </div>
                </div>
                <div class="hidden py-[23px] sm:block">
                    <h2 class="pb-3 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Тематики</h2>
                    <n-select :options="channelSubjects" :theme-overrides="selectThemeOverrides" placeholder="Все тематики"/>
                </div>
                <button @click.prevent="additionalFilter = !additionalFilter" class="hidden sm:block px-6 py-3.5 w-full bg-purple-600 transition hover:bg-purple-800 rounded-3xl justify-start items-start text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Дополнительный фильтр</button>
                <transition>
                <div v-show="additionalFilter" class="additional_filter">
                    <div class="py-2">
                        <h2 class="mb-12 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Возраст канала от (мес.)</h2>
                        <n-slider :theme-overrides="sliderThemeOverrides" :max="36" :format-tooltip="formatTooltip" :show-tooltip="additionalFilter"/>
                    </div>
                    <div class="py-2">
                        <h2 class="mb-12 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Мужская аудитория</h2>
                        <n-slider :theme-overrides="sliderThemeOverrides" :max="90" :format-tooltip="formatTooltipPercent" :show-tooltip="additionalFilter"/>
                    </div>
                    <div class="py-2">
                        <h2 class="mb-12 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Женская аудитория</h2>
                        <n-slider :theme-overrides="sliderThemeOverrides" :max="90" :format-tooltip="formatTooltipPercent" :show-tooltip="additionalFilter"/>
                    </div>
                    <div class="py-2">
                        <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Подписчиков</h2>
                        <n-input
                            pair
                            separator="-"
                            :placeholder="placeholder"
                            clearable
                            :theme-overrides="inputThemeOverrides"
                        />
                    </div>
                    <div class="py-2">
                        <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Средний охват поста (24 часа)</h2>
                        <n-input
                            pair
                            separator="-"
                            :placeholder="placeholder"
                            clearable
                            :theme-overrides="inputThemeOverrides"
                        />
                    </div>
                    <button class="my-6 px-6 py-2 w-full bg-purple-600 transition hover:bg-purple-800 rounded-3xl justify-start items-start text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Искать</button>
                    <div class="flex justify-center">
                        <button class="text-violet-100 text-xs font-normal font-['Open Sans'] underline leading-none">Очистить форму</button>
                    </div>
                </div>
                </transition>
            </div>
            <div class="channels">
                <div class="hidden sm:block total text-right text-violet-100 text-sm font-normal font-['Open Sans'] leading-normal">Всего каналов {{count}}</div>
                <div class="hidden gap-x-3 filter_buttons sm:flex">
                    <SortButton v-for="title in SORT_DATA" :title="title"/>
                </div>
                <CatalogChannels/>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
.additional_filter{
    border-radius: 25px;
    border: 1px solid  #EAE0FF;
    padding: 19px 25px;
    margin-top: 23px;
}
.catalog{
    grid-template-columns: 3fr 9fr;
    @media screen and (max-width: 640px) {
        grid-template-columns: 1fr;
        justify-items: center;
    }
    .filter{
        @media screen and (max-width: 640px) {
            padding: 0 15px;
        }
        min-width: 0;
    }
    .channels{
        @media screen and (max-width: 640px) {
            padding: 0 15px;
        }
        min-width: 0;
    }
}
.v-enter-active,
.v-leave-active {
    transition: all 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
