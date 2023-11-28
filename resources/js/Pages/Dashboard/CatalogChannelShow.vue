<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiCartOutline, mdiEyeOutline, mdiHeartOutline, mdiQrcodeScan, mdiRepeat, mdiStar} from '@mdi/js';
import {NSelect, NTabPane, NTabs} from "naive-ui";
import {nTabSegmentsThemeOverrides, selectThemeOverrides} from "@/themeOverrides.js";
import {useMainStore} from "@/stores/main.js";
import {ref} from "vue";
import InfoCard from "@/Components/Dashboard/InfoCard.vue";

const props = defineProps({
  channel: Object
})

const store = useMainStore()
const format = ref(1)
const count = ref(1)
const activeButton = ref('info');

const explain = ['','1 часа в топе / 24 часа в лент', '2 часа в топе / 48 часа в лент', "3 часа в топе / 72 часа в лент"]
</script>

<template>
    <AppLayout>
         <div>
            <div class="wrapper w-full sm:my-24 my-8 sm:p-0 p-2">
                <div class="card sm:p-12 p-2 pb-8">
                    <div class="flex flex-wrap justify-between items-start">
                        <div class="flex gap-x-8">
                            <img class="rounded-full w-[90px] h-[90px]" :src="channel.avatar" alt="">
                            <div class="flex flex-col gap-y-6">
                                <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">{{channel.channel_name}}</h1>
                                <div class="flex gap-x-4">
                                    <div class="w-6 h-6 bg-violet-100 rounded-full"></div>
                                    <div class="w-6 h-6 bg-violet-100 rounded-full"></div>
                                    <div class="w-6 h-6 bg-violet-100 rounded-full"></div>
                                </div>
                                <a :href="channel.url" class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">{{channel.channel_url}}</a>
                            </div>
                        </div>
                        <div class="flex flex-col gap-y-4">
                            <div class="flex gap-x-4 text-violet-100">
                                <div class="text-base font-normal font-['Open Sans'] leading-tight flex items-center gap-x-1"><BaseIcon :path="mdiEyeOutline" size="25"/>9,8К</div>
                                <div class="text-base font-normal font-['Open Sans'] leading-tight flex items-center gap-x-1"><BaseIcon :path="mdiHeartOutline"  size="25"/>966</div>
                                <BaseIcon :path="mdiRepeat" size="25"/>
                            </div>
                            <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">Язык:<span class="font-normal pl-2">{{ channel.language }}</span></div>
                            <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">Категория:<span class="font-normal pl-2">{{ channel.topic }}</span></div>
                            <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">Вид ссылки:<span class="font-normal pl-2">Приватный канал</span></div>
                        </div>
                    </div>
                    <div class="sm:w-1/2">
                        <h1 class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-loose">Купить интеграцию в этом канале</h1>
                        <div class="flex flex-wrap gap-x-14 w-full">
                            <div class="w-60">
                                <n-select v-model:value="format" :theme-overrides="selectThemeOverrides" :value="format" :options="store.format"/>
                                <div class="pl-3 pt-2 text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">{{ explain[format] }}</div>
                            </div>
                            <div class="w-60">
                                <n-select  v-model:value="count" :theme-overrides="selectThemeOverrides" :value="store.count[0].value" :options="store.count"/>
                            </div>
                        </div>
                        <div class="flex flex-wrap justify-between pt-8">
                            <h1 class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-loose">Стоимость публикации:</h1>
                            <h1 class="text-right text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">9 600 ₽</h1>
                        </div>
                        <div class="flex flex-wrap gap-4 pt-4">
                            <button class="text-white text-lg font-bold font-['Open Sans'] leading-normal px-6 py-3.5 bg-purple-600 rounded-3xl items-center inline-flex gap-x-2.5">Заказать <BaseIcon fill="white" size="20" :path="mdiCartOutline"/></button>
                            <button class="text-white text-lg font-bold font-['Open Sans'] leading-normal px-6 py-3.5 border  rounded-3xl items-center inline-flex gap-x-2.5">Купить по QR <BaseIcon fill="white" size="20" :path="mdiQrcodeScan"/></button>
                        </div>
                    </div>
                </div>
            </div>
         </div>
        <div class="segment">
            <n-tabs :animated="true" :theme-overrides="nTabSegmentsThemeOverrides" type="segment">
                <n-tab-pane name="info">
                    <template #tab>
                        <button @click.prevent="activeButton = 'info'" :class="['tab-button', 'transition', 'text-violet-100', 'text-lg', 'font-bold', 'font-[\'Open Sans\']', 'leading-normal', activeButton === 'info' ? 'active' : '']">Информация</button>
                    </template>
                    <div class="flex justify-center">
                        <p class="sm:w-1/2 w-full py-6 text-center text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">Авторитет и известность автора. Живая и активная аудитория. Посты о красоте и здоровье, косметологии и уходе за кожей и волосами, эстетической медицине и пластической хирургии, дерматологии и трихологии.</p>
                    </div>
                    <div class="flex justify-center gap-x-8">
                        <InfoCard>
                            <h2 class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Оценка</h2>
                            <h3 class="text-center text-violet-100 text-xs font-normal font-['Open Sans'] leading-none">отзывов</h3>
                            <div class="flex gap-x-1 text-violet-100 text-base font-bold font-['Open Sans'] leading-tight"><BaseIcon :path="mdiStar" fill="#FFB800"/>4.8</div>
                        </InfoCard>
                        <InfoCard>
                            <h2 class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Выявлено</h2>
                            <h3 class="text-center text-violet-100 text-xs font-normal font-['Open Sans'] leading-none">заявок</h3>
                            <h1 class="flex gap-x-1 text-violet-100 text-base font-bold font-['Open Sans'] leading-tight">734</h1>
                        </InfoCard>
                    </div>
                    <div class="flex flex-wrap mt-4 justify-center gap-8">
                        <InfoCard>
                            <h2 class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Подписчики</h2>
                            <h3 class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none">146 774</h3>
                        </InfoCard>
                        <InfoCard>
                            <h2 class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Просмотры на<br> пост</h2>
                            <h3 class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none">12.8К</h3>
                        </InfoCard>
                        <InfoCard>
                            <h2 class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">ER</h2>
                            <h3 class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none">8.74%</h3>
                        </InfoCard>
                        <InfoCard>
                            <h2 class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Публикаций
                                <br> в день</h2>
                            <h3 class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none">146 774</h3>
                        </InfoCard>
                        <InfoCard>
                            <h2 class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">СРМ</h2>
                            <h3 class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none">0.35 ₽</h3>
                        </InfoCard>
                    </div>
                </n-tab-pane>
                <n-tab-pane name="stat">
                    <template #tab>
                        <button @click.prevent="activeButton = 'stat'" :class="['tab-button', 'transition', 'text-violet-100', 'text-lg', 'font-bold', 'font-[\'Open Sans\']', 'leading-normal', activeButton === 'stat' ? 'active' : '']">Статистика</button>
                    </template>
                </n-tab-pane>
                <n-tab-pane name="review">
                    <template #tab>
                        <button @click.prevent="activeButton = 'review'" :class="['tab-button' , 'transition', 'text-violet-100', 'text-lg', 'font-bold', 'font-[\'Open Sans\']', 'leading-normal', activeButton === 'review' ? 'active' : '']">Отзывы</button>
                    </template>
                </n-tab-pane>
            </n-tabs>
        </div>
    </AppLayout>
</template>
<style scoped>
.wrapper{
    position: relative;
    overflow: hidden;
    &::before {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: 60px;
        bottom: 0;
        border-radius: 0 75px 75px 75px;
        background: radial-gradient(
            278.82% 137.51% at 1.95% 3.59%,
            rgba(255, 255, 255, 0.26) 0%,
            rgba(255, 255, 255, 0) 100%
        );
        backdrop-filter: blur(21px);
        transform: rotate(7deg);
        z-index: -1;

    }
    @media screen and (max-width: 640px){
        &::before {
            display: none;
        }
    }
    .card{
        border-radius: 0 75px 75px 75px;
        border: 2px solid #FFFFFF;
        background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.40) 0%, rgba(81, 63, 255, 0.00) 100%);
        backdrop-filter: blur(15px);
    }
}
.tab-button{
    border-radius: 100px;
    border: 1px solid  #6522D9;
    background: transparent;
    padding: 10px 25px;
    width: 100%;
    &:hover{
        background: #301864;
    }
}
.tab-button.active{
    border-radius: 100px;
    border: 1px solid  #6522D9;
    background: #301864;
    padding: 10px 25px;
}
</style>
