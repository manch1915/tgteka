<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {
    mdiCartMinus,
    mdiCartPlus,
    mdiEyeOutline,
    mdiHeartOutline,
    mdiQrcodeScan,
    mdiStar
} from '@mdi/js';
import {NSelect, NTabPane, NTabs, useMessage} from "naive-ui";
import {nTabSegmentsThemeOverrides, selectThemeOverrides} from "@/themeOverrides.js";
import {computed, onMounted, ref, watch} from "vue";
import InfoCard from "@/Components/Dashboard/InfoCard.vue";
import { saveCart, loadCart, isInCart as checkInCart, generateFormatArray } from "@/utilities/cartUtilities.js";
import { Chart,Title,Tooltip, Legend, BarElement, LinearScale, CategoryScale , PointElement, LineElement } from 'chart.js';
import { Line } from 'vue-chartjs'
import "chartjs-plugin-style";

Chart.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale,PointElement, LineElement);
const props = defineProps({
    channel: Object,
    countValue: {
        default: 1,
        required: false,
    },
    formatValue: {
        required: false,
    },
    favorites_count: Number
});

const countValue = ref(props.countValue);
const cartUpdateKey = ref(0);
const fav = ref(false);
const message = useMessage();
const format = computed(() => generateFormatArray(props.channel));
const formatValue = ref(format.value[0]?.value || null);

const removeCart = (cart, index, channel) => {
    cart.splice(index, 1);
    message.info(`Канал ${channel.channel_name} был удален из корзины.`);
    saveCart(cart);
};

const toggleChannelInCart = (channel) => {
    const cart = loadCart();
    const index = cart.findIndex((ch) => ch.id === channel.id);

    if (index > -1 && formatValue.value === cart[index].format && countValue.value === cart[index].count) {
        removeCart(cart, index, channel);
    } else {
        updateCart(cart, channel, countValue.value, formatValue.value);
    }

    cartUpdateKey.value++;
};

const count = [
    { label: '1', value: 1 },
    { label: '2', value: 2 },
    { label: '3', value: 3 },
    { label: '4', value: 4 },
];

const isInCart = (channel) => {
    const dummy = cartUpdateKey.value;
    return checkInCart(channel);
};

const addChannelToFavorites = async (channel) => {
    try {
        const response = await axios.post(route("catalog.channels.favorite"), { channel_id: channel.id });

        if (response.data.status === "success") {
            const isFav = response.data.message.includes("added");
            const operation = isFav ? "добавлен в избранное" : "удален из избранних";
            message.info(`Канал ${channel.channel_name} ${operation}`);
            fav.value = isFav;
        } else {
            message.error("Возникла проблема с добавлением канала в избранное.");
        }
    } catch (error) {
        message.error("Произошла ошибка: ", error);
    }
};

const updateCart = (cart, channel, count, format) => {
    const channelIndex = cart.findIndex((ch) => ch.id === channel.id);

    if (channelIndex > -1) {
        cart[channelIndex] = Object.assign({}, channel, { count: count, format: format });
        message.info(`Канал ${channel.channel_name} было обновлено в корзине.`);
    } else {
        const channelData = Object.assign({}, channel, { count: count, format: format });
        cart.push(channelData);
        message.info(`Канал ${channel.channel_name} был добавлен в корзину.`);
    }

    saveCart(cart);
};
const totalPrice = computed(() => {
    const selectedFormat = format.value.find((item) => item.value === formatValue.value);
    const pricePerUnit = selectedFormat ? props.channel[selectedFormat.value] : 0;
    return pricePerUnit * countValue.value;
});
watch(countValue, (newValue) => {
    let cart = loadCart();
    if (isInCart(props.channel)) {
        updateCart(cart, props.channel, newValue, formatValue.value)
    }
});

watch(formatValue, (newValue) => {
    let cart = loadCart();
    if (isInCart(props.channel)) {
        updateCart(cart, props.channel, countValue.value, newValue)
    }
});
const activeButton = ref('info');
const explain = ['','1 часа в топе / 24 часа в лент', '2 часа в топе / 48 часа в лент', "3 часа в топе / 72 часа в лент"]

let glowEffectApplied = ref(false);

const applyGlowEffect = () => {
    let draw = Chart.controllers.line.prototype.draw;
    Chart.controllers.line.prototype.draw = function() {
        let chart = this.chart;
        let ctx = chart.ctx;
        let _stroke = ctx.stroke;
        ctx.stroke = function() {
            ctx.save();
            ctx.shadowColor = '#8729FF';
            ctx.shadowBlur = 15;
            _stroke.apply(this, arguments);
            ctx.restore();
        };
        draw.apply(this, arguments);
        ctx.stroke = _stroke;
    };

    glowEffectApplied.value = true;
};

const channelStats = ref({})

let chartDataSubs = ref({
    labels: [],
    datasets: [{
        label: 'Subscribers',
        data: [],
        borderColor: '#007BFF',
        fill: false,
    }]
})

let chartDataAvg = ref({
    labels: [],
    datasets: [{
        label: 'Avg Post Reach',
        data: [],
        borderColor: '#FF4500',
        fill: false,
    }]
})

let chartDataER = ref({
    labels: [],
    datasets: [{
        label: 'ER',
        data: [],
        borderColor: '#FFD700',
        fill: false,
    }]
})

const fetchChannelStats = async () => {
    try {
        const response = await axios.get(route('catalog.channel.stats.all', props.channel.id))
        channelStats.value = response.data;

        chartDataSubs.value = {
            labels: channelStats.value.subscribers.map(item => item.period),
            datasets: [{
                label: 'Subscribers',
                data: channelStats.value.subscribers.map(item => item.participants_count),
                borderColor: '#007BFF',
                fill: false,

            }]
        };
        chartDataAvg.value = {
            labels: channelStats.value.avg_posts_reach.map(item => item.period),
            datasets: [{
                label: 'avg_posts_reach',
                data: channelStats.value.avg_posts_reach.map(item => item.avg_posts_reach),
                borderColor: '#007BFF',
                fill: false,
            }]
        };
        chartDataER.value = {
            labels: channelStats.value.er.map(item => item.period),
            datasets: [{
                label: 'ER',
                data: channelStats.value.er.map(item => item.er),
                borderColor: '#FFD700',
                fill: false,
            }]
        };
    }
    catch (err) {
        console.error(err)
    }
}

onMounted(() => {
    fetchChannelStats();
});
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
                                <div class="text-base font-normal font-['Open Sans'] leading-tight flex items-center gap-x-1"><BaseIcon :path="mdiEyeOutline" size="25"/>{{channel.views_count}}</div>
                                <div class="text-base font-normal font-['Open Sans'] leading-tight flex items-center gap-x-1"><BaseIcon :path="mdiHeartOutline"  size="25"/>{{favorites_count}}</div>
                            </div>
                            <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">Язык:<span class="font-normal pl-2">{{ channel.language }}</span></div>
                            <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">Категория:<span class="font-normal pl-2">{{ channel.topic.title }}</span></div>
                            <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">Вид ссылки:<span class="font-normal pl-2">Приватный канал</span></div>
                        </div>
                    </div>
                    <div class="sm:w-1/2">
                        <h1 class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-loose">Купить интеграцию в этом канале</h1>
                        <div class="flex flex-wrap gap-x-14 w-full">
                            <div class="w-60">
                                <n-select v-model:value="formatValue" :theme-overrides="selectThemeOverrides" :options="format"/>
                                <div class="pl-3 pt-2 text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">{{ explain[format] }}</div>
                            </div>
                            <div class="w-60">
                                <n-select  v-model:value="countValue" :theme-overrides="selectThemeOverrides" :options="count"/>
                            </div>
                        </div>
                        <div class="flex flex-wrap justify-between pt-8">
                            <h1 class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-loose">Стоимость публикации:</h1>
                            <h1 class="text-right text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">{{totalPrice}} ₽</h1>
                        </div>
                        <div class="flex flex-wrap gap-4 pt-4">
                            <button @click.prevent="toggleChannelInCart(channel)" class="text-white text-lg font-bold font-['Open Sans'] leading-normal px-6 py-3.5 bg-purple-600 rounded-3xl items-center inline-flex gap-x-2.5">Заказать<BaseIcon size="25" :path="isInCart(channel) ? mdiCartMinus : mdiCartPlus"/></button>
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
                        <p class="sm:w-1/2 w-full py-6 text-center text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">{{channel.description}}</p>
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
                            <h1 class="flex gap-x-1 text-violet-100 text-base font-bold font-['Open Sans'] leading-tight">

<!--                              todo orders count for channel  -->
                            </h1>
                        </InfoCard>
                    </div>
                    <div class="flex flex-wrap mt-4 justify-center gap-8">
                        <InfoCard>
                            <h2 class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Подписчики</h2>
                            <h3 class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none">146 774</h3>
                        </InfoCard>
                        <InfoCard>
                            <h2 class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Просмотры на<br> пост</h2>
                            <h3 class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none">{{ }}</h3>
                        </InfoCard>
                        <InfoCard>
                            <h2 class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">ER</h2>
                            <h3 class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none">{{  }}%</h3>
                        </InfoCard>
                        <InfoCard>
                            <h2 class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Публикаций</h2>
                            <h3 class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none">{{ }}</h3>
                        </InfoCard>
                        <InfoCard>
                            <h2 class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">СРМ</h2>
                            <h3 class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none"> ₽</h3>
                        </InfoCard>
                    </div>
                </n-tab-pane>
                <n-tab-pane name="stat">
                    <template #tab>
                        <button @click.prevent="activeButton = 'stat'" :class="['tab-button', 'transition', 'text-violet-100', 'text-lg', 'font-bold', 'font-[\'Open Sans\']', 'leading-normal', activeButton === 'stat' ? 'active' : '']">Статистика</button>
                    </template>
                    <div class="grid grid-cols-2">
                        <div>
                            <h1 class="text-center text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"> График с текущим количеством подписчиков</h1>
                            <Line :data="chartDataSubs"/>
                        </div>
                        <div>
                            <h1 class="text-center text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Охват за 24 часа</h1>
                            <Line :data="chartDataAvg"/>
                        </div>
                        <div>
                            <h1 class="text-center text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">ER% — вовлеченность по взаимодействиям</h1>
                            <Line :data="chartDataER"/>
                        </div>
                    </div>
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
