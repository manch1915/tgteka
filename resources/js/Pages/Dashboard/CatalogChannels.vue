<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import SortButton from '@/Components/Dashboard/SortButton.vue'
import {closeModal} from 'jenesius-vue-modal'
import SearchBar from '@/Components/Dashboard/SearchBar.vue'
import {NDrawer, NDrawerContent, NInput, NSelect, NSlider, NSpace} from 'naive-ui'
import {
    inputThemeOverrides,
    selectThemeOverrides,
    sliderThemeOverrides
} from '@/themeOverrides.js'
import {computed, ref} from 'vue'
import CatalogChannels from '@/Components/Dashboard/CatalogChannels.vue'
import {useMainStore} from '@/stores/main.js'
import {useChannelStore} from "@/stores/channelStore.js";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiArrowLeftBold} from "@mdi/js";
import {Head} from "@inertiajs/vue3";

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

const applyFilters = async () =>{
    active.value = false;
    await channelStore.fetchChannels(1, channelStore.additionalFilter)
}

const allowOnlyNumbers = (value) => {
    return value === '' || /^[0-9]+$/.test(value);
};

const clearFilters = async () => {
    channelStore.resetFilters();
    await channelStore.fetchChannels(1, channelStore.additionalFilter);
}

const tab = ref('channel')

const togglePeerType = (peerType) => {
    if (channelStore.additionalFilter.peerType === peerType){
        return
    }
    channelStore.resetFilters();
    channelStore.additionalFilter.peerType = peerType
    tab.value = peerType
}

const active = ref(false);
const placement = ref("right");
const activate = (place) => {
    if (window.matchMedia('(max-width: 640px)').matches) {
        active.value = true;
        placement.value = place;
    }
};

</script>

<template>
    <Head>
        <title>Каталог Telegram-каналов</title>
    </Head>
    <AppLayout>
        <n-drawer v-model:show="active" close-on-esc width="100%" :placement="placement" :theme-overrides="{color: '#070C29'}">
            <n-drawer-content>
                <template #header>
                    <div @click.prevent="active = false" class="flex cursor-pointer">
                        <base-icon :path="mdiArrowLeftBold" size="24"/>
                        <p class="text-violet-100 text-lg font-bold font-['Open Sans']">Фильтр</p>
                    </div>
                </template>
<!--                todo additional_filter to component -->
                <div class="additional_filter">
                    <div class="py-2">
                        <h2 class="mb-12 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Возраст канала от (мес.)</h2>
                        <n-slider v-model:value="channelStore.mainFilter.channel_creation_date" :theme-overrides="sliderThemeOverrides" :max="36" :format-tooltip="formatTooltip" :show-tooltip="additionalFilter"/>
                    </div>
                    <div class="py-2">
                        <h2 class="mb-12 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Мужская аудитория</h2>
                        <n-slider v-model:value="channelStore.mainFilter.male_percentage" :theme-overrides="sliderThemeOverrides" :max="90" :format-tooltip="formatTooltipPercent" :show-tooltip="additionalFilter"/>
                    </div>
                    <div class="py-2">
                        <h2 class="mb-12 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Женская аудитория</h2>
                        <n-slider v-model:value="channelStore.mainFilter.female_percentage" :theme-overrides="sliderThemeOverrides" :max="90" :format-tooltip="formatTooltipPercent" :show-tooltip="additionalFilter"/>
                    </div>
                    <n-space vertical size="large" class="mt-6">
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Подписчиков</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.participants_count"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Средний охват поста (24 часа)</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.adv_post_reach_24h"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Процент вовлеченности подписчиков (ERR %)</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.err_percent"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Коэффициент вовлеченности подписчиков во взаимодействия с постом (реакция, пересылка, комментарий) </h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.er_percent"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Cуммарный дневной охват</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.daily_reach"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Индекс цитирования (ИЦ)</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.ci_index"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество упоминаний канала в других каналах</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.mentions_count"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество репостов в другие каналы</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.forwards_count"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество каналов, упоминающих данный канал</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.mentioning_channels_count"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Общее количество неудаленных публикаций в канале</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.posts_count"
                            />
                        </div>
                    </n-space>
                    <n-space v-if="channelStore.additionalFilter.peerType === 'chat'" vertical size="large" class="mt-6">
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество участников чата на момент запроса</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.participants_count"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">DAU, кол-во активных участников за сутки</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.dau"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">WAU, кол-во активных участников за неделю</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.wau"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">MAU, кол-во активных участников за месяц</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.mau"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество участников "онлайн" в дневное время</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.online_count_day_time"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество участников "онлайн" в ночное время</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.online_count_night_time"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество сообщений за вчера</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.messages_count_yesterday"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество сообщений за последнюю неделю</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.messages_count_last_week"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество сообщений за последний месяц</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.messages_count_last_month"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество сообщений всего</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.messages_count_total"
                            />
                        </div>
                    </n-space>

                    <button @click.prevent="applyFilters" class="my-6 px-6 py-2 w-full bg-purple-600 transition hover:bg-purple-800 rounded-3xl justify-start items-start text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Искать</button>
                    <div class="flex justify-center">
                        <button @click.prevent="clearFilters" class="text-violet-100 text-xs font-normal font-['Open Sans'] underline leading-none">Очистить форму</button>
                    </div>
                </div>
            </n-drawer-content>
        </n-drawer>
        <div class="sm:py-20 py-10 text-center">
            <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Каталог Telegram-каналов</h1>
            <p class="sm:hidden text-center block total pt-4 text-violet-100 text-sm font-normal font-['Open Sans'] leading-normal">Всего каналов {{count}}</p>
        </div>
        <div class="grid catalog">
            <div class="w-full pr-0 filter sm:pr-10">
                <div class="sm:block flex justify-between gap-x-12 flex-wrap">
                    <div @click.prevent="activate('right')" class="flex cursor-pointer items-center justify-start pb-[24px] gap-x-2.5 sm:justify-between sm:gap-x-0">
                        <h1 class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">Фильтр</h1>
                        <img src="/images/setting.svg" alt="setting">
                    </div>
                    <div class="inline w-full sm:hidden">
                        <n-select :options="channelSubjects" :theme-overrides="selectThemeOverrides" placeholder="Все тематики"/>
                    </div>
                </div>
                <div class="pb-6">
                    <SearchBar @search="searchHandler" class="py-2"/>

                </div>
                <div class="hidden py-[23px] sm:block">
                    <h2 class="pb-3 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Тематики</h2>
                    <n-select :options="channelSubjects" :theme-overrides="selectThemeOverrides" placeholder="Все тематики"/>
                </div>
                <button @click.prevent="additionalFilter = !additionalFilter" class="hidden sm:block px-6 py-3.5 w-full bg-purple-600 transition hover:bg-purple-800 rounded-3xl justify-start items-start text-violet-100 sm:text-sm break-all text-lg font-bold font-['Open Sans'] leading-normal">Дополнительный фильтр</button>
                <transition>
                <div v-show="additionalFilter" class="additional_filter">
                    <div class="py-2">
                        <h2 class="mb-12 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Возраст канала от (мес.)</h2>
                        <n-slider v-model:value="channelStore.mainFilter.channel_creation_date" :theme-overrides="sliderThemeOverrides" :max="36" :format-tooltip="formatTooltip" :show-tooltip="additionalFilter"/>
                    </div>
                    <div class="py-2">
                        <h2 class="mb-12 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Мужская аудитория</h2>
                        <n-slider v-model:value="channelStore.mainFilter.male_percentage" :theme-overrides="sliderThemeOverrides" :max="90" :format-tooltip="formatTooltipPercent" :show-tooltip="additionalFilter"/>
                    </div>
                    <div class="py-2">
                        <h2 class="mb-12 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Женская аудитория</h2>
                        <n-slider v-model:value="channelStore.mainFilter.female_percentage" :theme-overrides="sliderThemeOverrides" :max="90" :format-tooltip="formatTooltipPercent" :show-tooltip="additionalFilter"/>
                    </div>
                    <n-space vertical size="large" class="mt-6">
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Подписчиков</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.participants_count"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Средний охват поста (24 часа)</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.adv_post_reach_24h"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Процент вовлеченности подписчиков (ERR %)</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.err_percent"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Коэффициент вовлеченности подписчиков во взаимодействия с постом (реакция, пересылка, комментарий) </h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.er_percent"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Cуммарный дневной охват</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.daily_reach"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Индекс цитирования (ИЦ)</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.ci_index"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество упоминаний канала в других каналах</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.mentions_count"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество репостов в другие каналы</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.forwards_count"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество каналов, упоминающих данный канал</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.mentioning_channels_count"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Общее количество неудаленных публикаций в канале</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.posts_count"
                            />
                        </div>
                    </n-space>
                    <n-space v-if="channelStore.additionalFilter.peerType === 'chat'" vertical size="large" class="mt-6">
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество участников чата на момент запроса</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.participants_count"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">DAU, кол-во активных участников за сутки</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.dau"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">WAU, кол-во активных участников за неделю</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.wau"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">MAU, кол-во активных участников за месяц</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.mau"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество участников "онлайн" в дневное время</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.online_count_day_time"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество участников "онлайн" в ночное время</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.online_count_night_time"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество сообщений за вчера</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.messages_count_yesterday"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество сообщений за последнюю неделю</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.messages_count_last_week"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество сообщений за последний месяц</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.messages_count_last_month"
                            />
                        </div>
                        <div>
                            <h2 class="mb-3 text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Количество сообщений всего</h2>
                            <n-input
                                pair
                                separator="-"
                                :placeholder="placeholder"
                                clearable
                                :theme-overrides="inputThemeOverrides"
                                :allow-input="allowOnlyNumbers"
                                v-model:value="channelStore.additionalFilter.messages_count_total"
                            />
                        </div>
                    </n-space>

                    <button @click.prevent="applyFilters" class="my-6 px-6 py-2 w-full bg-purple-600 transition hover:bg-purple-800 rounded-3xl justify-start items-start text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Искать</button>
                    <div class="flex justify-center">
                        <button @click.prevent="clearFilters" class="text-violet-100 text-xs font-normal font-['Open Sans'] underline leading-none">Очистить форму</button>
                    </div>
                </div>
                </transition>
            </div>
            <div class="channels">
                <div class="hidden sm:block total text-right text-violet-100 text-sm font-normal font-['Open Sans'] leading-normal">Всего каналов {{count}}</div>
                <div class="hidden gap-3 filter_buttons sm:flex flex-wrap">
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
