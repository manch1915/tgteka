<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SortButton from "@/Components/Dashboard/SortButton.vue";
import {closeModal} from "jenesius-vue-modal";
import SearchBar from "@/Components/Dashboard/SearchBar.vue";
import {NCheckbox, NInput, NSelect, NSlider} from "naive-ui";
import {
    checkboxThemeOverrides,
    inputThemeOverrides,
    selectThemeOverrides,
    sliderThemeOverrides
} from "@/themeOverrides.js";
import {ref} from "vue";

const sortData = [
    'Рейтинг',
    'ER',
    'Просмотры',
    'Подписчики',
    'Цена',
    'CPМ'
]
closeModal()

const formatTooltip = value => {
    return `${value}+`
}
const formatTooltipProcent = value => {
    return `> ${value}%`
}
const additionalFilter = ref(false)
const placeholder = ['от', 'до']
</script>

<template>
    <AppLayout>
        <div class="text-center py-20">
            <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Каталог Telegram-каналов</h1>
        </div>
        <div class="catalog grid">
            <div class="filter pr-10">
                <div class="pb-[24px] flex justify-between items-center">
                    <h1 class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">Фильтр</h1>
                    <img src="/images/setting.svg" alt="">
                </div>
                <div class="pb-6">
                    <SearchBar @search="" class="py-2"/>
                    <div class="flex gap-x-2 items-center">
                        <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                        <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Искать также в описании</p>
                    </div>
                </div>
                <div class="py-[23px]">
                    <h2 class="pb-3 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Тематики</h2>
                    <n-select :theme-overrides="selectThemeOverrides" placeholder="Все тематики"/>
                </div>
                <button @click.prevent="additionalFilter = !additionalFilter" class="px-6 py-3.5 w-full bg-purple-600 transition hover:bg-purple-800 rounded-3xl justify-start items-start text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Дополнительный фильтр</button>
                <transition>
                <div v-show="additionalFilter" class="additional_filter">
                    <div class="py-2">
                        <h2 class="mb-12 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Возраст канала от (мес.)</h2>
                        <n-slider :theme-overrides="sliderThemeOverrides" :max="36" :format-tooltip="formatTooltip" :show-tooltip="additionalFilter"/>
                    </div>
                    <div class="py-2">
                        <h2 class="mb-12 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Мужская аудитория</h2>
                        <n-slider :theme-overrides="sliderThemeOverrides" :max="90" :format-tooltip="formatTooltipProcent" :show-tooltip="additionalFilter"/>
                    </div>
                    <div class="py-2">
                        <h2 class="mb-12 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Женская аудитория</h2>
                        <n-slider :theme-overrides="sliderThemeOverrides" :max="90" :format-tooltip="formatTooltipProcent" :show-tooltip="additionalFilter"/>
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
                    <div class="py-2 border-t border-b border-violet-100 border-opacity-40 flex gap-x-3">
                        <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                        <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Верифицированный</p>
                        <img src="/images/Icon.svg" alt="">
                    </div>
                    <button class="my-6 px-6 py-2 w-full bg-purple-600 transition hover:bg-purple-800 rounded-3xl justify-start items-start text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Искать</button>
                    <div class="flex justify-center">
                        <button class="text-violet-100 text-xs font-normal font-['Open Sans'] underline leading-none">Очистить форму</button>
                    </div>
                </div>
                </transition>
            </div>
            <div class="channels">
                <div class="total text-right text-violet-100 text-sm font-normal font-['Open Sans'] leading-normal">Всего каналов 63 448</div>
                <div class="filter_buttons flex gap-x-3">
                    <SortButton v-for="title in sortData" :title="title"/>
                </div>
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
    .filter{
        min-width: 0;
    }
    .channels{
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
