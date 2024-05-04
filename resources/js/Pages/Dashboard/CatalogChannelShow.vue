<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {
    mdiCartPlus,
    mdiCartRemove,
    mdiEyeOutline,
    mdiHeartOutline,
    mdiStar,
} from "@mdi/js";
import { NSelect, NTabPane, NTabs, useMessage } from "naive-ui";
import {
    nTabSegmentsThemeOverrides,
    selectThemeOverrides,
} from "@/themeOverrides.js";
import { computed, onMounted, ref, watch } from "vue";
import InfoCard from "@/Components/Dashboard/InfoCard.vue";
import {
    generateFormatArray,
    isInCart as checkInCart,
    loadCart,
    saveCart,
} from "@/utilities/cartUtilities.js";
import {
    BarElement,
    CategoryScale,
    Chart,
    DatasetController,
    Legend,
    LinearScale,
    LineElement,
    PointElement,
    Title,
    Tooltip,
} from "chart.js";
import { Line } from "vue-chartjs";
import Reviews from "@/Components/Dashboard/ChannelTab/Reviews.vue";
import axios from "axios";
import { Head } from "@inertiajs/vue3";

Chart.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement
);
const props = defineProps({
    channel: Object,
    formatValue: {
        required: false,
    },
    favorites_count: Number,
});

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

    if (index > -1 && formatValue.value === cart[index].format) {
        removeCart(cart, index, channel);
    } else {
        updateCart(cart, channel, formatValue.value);
    }

    cartUpdateKey.value++;
};

const count = [
    { label: "1", value: 1 },
    { label: "2", value: 2 },
    { label: "3", value: 3 },
    { label: "4", value: 4 },
];

const isInCart = (channel) => {
    const dummy = cartUpdateKey.value;
    return checkInCart(channel);
};

const updateCart = (cart, channel, format) => {
    const channelIndex = cart.findIndex((ch) => ch.id === channel.id);

    if (channelIndex > -1) {
        cart[channelIndex] = Object.assign({}, channel, { format: format });
        message.info(`Канал ${channel.channel_name} было обновлено в корзине.`);
    } else {
        const channelData = Object.assign({}, channel, { format: format });
        cart.push(channelData);
        message.info(`Канал ${channel.channel_name} был добавлен в корзину.`);
    }

    saveCart(cart);
};
const totalPrice = computed(() => {
    const selectedFormat = format.value.find(
        (item) => item.value === formatValue.value
    );
    return selectedFormat ? props.channel[selectedFormat.value] : 0;
});

watch(formatValue, (newValue) => {
    let cart = loadCart();
    if (isInCart(props.channel)) {
        updateCart(cart, props.channel, newValue);
    }
});

const activeButton = ref("info");
const explain = [
    "",
    "1 часа в топе / 24 часа в лент",
    "2 часа в топе / 48 часа в лент",
    "3 часа в топе / 72 часа в лент",
];

const applyShadowEffect = (ctx) => {
    const _stroke = ctx.stroke;
    ctx.stroke = function () {
        ctx.save();
        ctx.shadowColor = "#8729FF";
        ctx.shadowBlur = 6;
        ctx.shadowOffsetX = 0;
        ctx.shadowOffsetY = 2;
        _stroke.apply(this, arguments);
        ctx.restore();
    };
};

const extendDatasetController = () => {
    const originalDraw = DatasetController.prototype.draw;
    DatasetController.prototype.draw = function () {
        originalDraw.apply(this, arguments);
        applyShadowEffect(this.chart.ctx);
    };
};

const chartOptionsNoLabels = {
    scales: {
        x: {
            // ...
        },
        y: {
            grid: {
                display: true, // Set to true to show only horizontal grid lines
            },
        },
    },
    plugins: {
        legend: {
            display: false, // Set to false to hide dataset labels
        },
    },
};

const channelStats = ref({});

let chartDataSubs = ref({
    labels: [],
    datasets: [
        {
            label: "Subscribers",
            data: [],
            borderColor: "#007BFF",
            fill: false,
        },
    ],
});

let chartDataAvg = ref({
    labels: [],
    datasets: [
        {
            label: "Avg Post Reach",
            data: [],
            borderColor: "#FF4500",
            fill: false,
        },
    ],
});

let chartDataER = ref({
    labels: [],
    datasets: [
        {
            label: "ER",
            data: [],
            borderColor: "#FFD700",
            fill: false,
        },
    ],
});

const fetchChannelStats = async () => {
    try {
        const response = await axios.get(
            route("catalog.channel.stats.all", props.channel.id)
        );
        channelStats.value = response.data;

        chartDataSubs.value = {
            labels: channelStats.value.subscribers.reverse().map((item) => {
                let date = new Date(item.period);
                return `${date.getMonth() + 1}-${date.getDate()}`;
            }),
            datasets: [
                {
                    label: "Subscribers",
                    data: channelStats.value.subscribers.map(
                        (item) => item.participants_count
                    ),
                    borderColor: "#8729FF",
                    fill: false,
                },
            ],
        };
        chartDataAvg.value = {
            labels: channelStats.value.avg_posts_reach.reverse().map((item) => {
                let date = new Date(item.period);
                return `${date.getMonth() + 1}-${date.getDate()}`;
            }),
            datasets: [
                {
                    label: "avg_posts_reach",
                    data: channelStats.value.avg_posts_reach.map(
                        (item) => item.avg_posts_reach
                    ),
                    borderColor: "#8729FF",
                    fill: false,
                },
            ],
        };
        chartDataER.value = {
            labels: channelStats.value.er.reverse().map((item) => {
                let date = new Date(item.period);
                return `${date.getMonth() + 1}-${date.getDate()}`;
            }),
            datasets: [
                {
                    label: "ER",
                    data: channelStats.value.er.map((item) => item.er),
                    borderColor: "#8729FF",
                    fill: false,
                },
            ],
        };
    } catch (err) {
        console.error(err);
    }
};

const ordersCount = ref(0);

const getChannelOrdersCount = () => {
    axios
        .get(
            route("catalog.channel.orders.count", {
                channelId: props.channel.id,
            })
        )
        .then((r) => (ordersCount.value = r.data))
        .catch((c) => console.log(c));
};

onMounted(() => {
    fetchChannelStats();
    getChannelOrdersCount();
    extendDatasetController();
});
</script>

<template>
    <Head>
        <title>{{ channel.channel_name }}</title>
    </Head>
    <AppLayout>
        <div class="px-2">
            <div>
                <div class="wrapper w-full sm:my-24 my-8 sm:p-0 p-2">
                    <div class="card sm:p-12 p-6 pb-8">
                        <div class="flex flex-wrap justify-between items-start">
                            <div class="flex sm:gap-x-8 gap-x-2">
                                <img
                                    class="rounded-full w-[90px] h-[90px]"
                                    :src="channel.avatar"
                                    alt=""
                                />
                                <div class="flex flex-col gap-y-6">
                                    <h1
                                        class="text-violet-100 lg:text-4xl text-lg font-bold font-['Open Sans'] leading-tight"
                                        style="word-break: break-word"
                                    >
                                        {{ channel.channel_name }}
                                    </h1>
                                    <div class="flex gap-x-4">
                                        <div
                                            class="w-6 h-6 bg-violet-100 rounded-full"
                                        ></div>
                                        <div
                                            class="w-6 h-6 bg-violet-100 rounded-full"
                                        ></div>
                                        <div
                                            class="w-6 h-6 bg-violet-100 rounded-full"
                                        ></div>
                                    </div>
                                    <a
                                        :href="channel.url"
                                        class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-tight"
                                    >{{ channel.channel_url }}</a
                                    >
                                </div>
                            </div>
                            <div class="flex flex-col gap-y-4">
                                <div class="flex gap-x-4 text-violet-100">
                                    <div
                                        class="text-base font-normal font-['Open Sans'] leading-tight flex items-center gap-x-1"
                                    >
                                        <BaseIcon
                                            :path="mdiEyeOutline"
                                            size="25"
                                        />{{ channel.views_count }}
                                    </div>
                                    <div
                                        class="text-base font-normal font-['Open Sans'] leading-tight flex items-center gap-x-1"
                                    >
                                        <BaseIcon
                                            :path="mdiHeartOutline"
                                            size="25"
                                        />{{ favorites_count }}
                                    </div>
                                </div>
                                <div
                                    class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight"
                                >
                                    Язык:<span class="font-normal pl-2">{{
                                        channel.language
                                    }}</span>
                                </div>
                                <div
                                    class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight"
                                >
                                    Категория:<span class="font-normal pl-2">{{
                                        channel.topic.title
                                    }}</span>
                                </div>
                                <div
                                    class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight"
                                >
                                    Вид ссылки:<span class="font-normal pl-2"
                                >Приватный канал</span
                                >
                                </div>
                            </div>
                        </div>
                        <div>
                            <h1
                                class="text-violet-100 sm:text-2xl text-lg mt-4 my-2 font-bold font-['Open Sans'] leading-tight"
                            >
                                Купить интеграцию в этом канале
                            </h1>
                            <div class="flex flex-wrap gap-x-14 w-full">
                                <div class="w-60">
                                    <n-select
                                        v-model:value="formatValue"
                                        :theme-overrides="selectThemeOverrides"
                                        :options="format"
                                    />
                                    <div
                                        class="pl-3 pt-2 text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                                    >
                                        {{ explain[format] }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-baseline gap-x-2 pt-8">
                                <h1
                                    class="text-violet-100 sm:text-2xl text-lg font-bold font-['Open Sans'] leading-tight"
                                >
                                    Стоимость публикации:
                                </h1>
                                <h1
                                    class="text-right text-violet-100 sm:text-3xl text-2xl font-bold font-['Open Sans'] leading-tight"
                                >
                                    {{ totalPrice }} ₽
                                </h1>
                            </div>
                            <div class="flex flex-wrap sm:justify-start justify-center gap-4 pt-4">
                                <button
                                    @click.prevent="toggleChannelInCart(channel)"
                                    class="text-white text-lg font-bold font-['Open Sans'] leading-normal px-6 py-3.5 btn_gradient-purple transition hover:bg-purple-800 rounded-3xl items-center inline-flex gap-x-2.5"
                                >
                                    {{
                                        isInCart(channel)
                                            ? "Удалить из корзины"
                                            : "Добавить в корзину"
                                    }}
                                    <BaseIcon
                                        size="25"
                                        :path="
                                        isInCart(channel)
                                            ? mdiCartRemove
                                            : mdiCartPlus
                                    "
                                    />
                                </button>
                                <!--                            <button class="text-white text-lg font-bold font-['Open Sans'] leading-normal px-6 py-3.5 border  rounded-3xl items-center inline-flex gap-x-2.5">Купить по QR <BaseIcon fill="white" size="20" :path="mdiQrcodeScan"/></button>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="segment">
                <n-tabs
                    :animated="true"
                    :theme-overrides="nTabSegmentsThemeOverrides"
                    type="segment"
                >
                    <n-tab-pane name="info">
                        <template #tab>
                            <button
                                @click.prevent="activeButton = 'info'"
                                :class="[
                                'tab-button',
                                'transition',
                                'text-violet-100',
                                'text-lg',
                                'font-bold',
                                'font-[\'Open Sans\']',
                                'leading-normal',
                                activeButton === 'info' ? 'active' : '',
                            ]"
                            >
                                Информация
                            </button>
                        </template>
                        <div class="flex justify-center px-1">
                            <p
                                class="sm:w-1/2 w-full py-6 text-center text-violet-100 text-xl font-normal font-['Open Sans'] leading-tight"
                            >
                                {{ channel.description }}
                            </p>
                        </div>
                        <div class="flex justify-center gap-x-8">
                            <InfoCard>
                                <h3
                                    class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Оценка отзывов
                                </h3>
                                <div
                                    class="flex gap-x-1 text-violet-100 text-base font-bold font-['Open Sans'] leading-tight"
                                >
                                    <BaseIcon :path="mdiStar" fill="#FFB800" />{{
                                        channel.rating
                                    }}
                                </div>
                            </InfoCard>
                            <InfoCard>
                                <h2
                                    class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Выявлено заявок
                                </h2>
                                <h1
                                    class="flex gap-x-1 text-violet-100 text-base font-bold font-['Open Sans'] leading-tight"
                                >
                                    {{ ordersCount }}
                                </h1>
                            </InfoCard>
                        </div>
                        <div class="flex flex-wrap mt-8 justify-center gap-8">
                            <InfoCard>
                                <h2
                                    class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Подписчики
                                </h2>
                                <h3
                                    v-if="channelStats.stats"
                                    class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none"
                                >
                                    {{ channelStats.stats.participants_count }}
                                </h3>
                            </InfoCard>
                            <InfoCard
                                v-if="
                                channelStats.stats &&
                                channelStats.stats.avg_post_reach
                            "
                            >
                                <h2
                                    class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Просмотры на<br />
                                    пост
                                </h2>
                                <h3
                                    v-if="channelStats.stats"
                                    class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none"
                                >
                                    {{ channelStats.stats.avg_post_reach }}
                                </h3>
                            </InfoCard>
                            <InfoCard
                                v-if="
                                channelStats.stats &&
                                channelStats.stats.er_percent
                            "
                            >
                                <h2
                                    class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    ER
                                </h2>
                                <h3
                                    v-if="channelStats.stats"
                                    class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none"
                                >
                                    {{ channelStats.stats.er_percent }}%
                                </h3>
                            </InfoCard>
                            <InfoCard
                                v-if="
                                channelStats.stats &&
                                channelStats.stats.posts_count
                            "
                            >
                                <h2
                                    class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Публикаций
                                </h2>
                                <h3
                                    class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none"
                                >
                                    {{ channelStats.stats.posts_count }}
                                </h3>
                            </InfoCard>
                            <InfoCard v-if="channel.cpm && channel">
                                <h2
                                    class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    СРМ
                                </h2>
                                <h3
                                    class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-none"
                                >
                                    {{ channel.cpm }}&nbsp;₽
                                </h3>
                            </InfoCard>
                        </div>
                    </n-tab-pane>
                    <n-tab-pane name="stat">
                        <template #tab>
                            <button
                                @click.prevent="activeButton = 'stat'"
                                :class="[
                                'tab-button',
                                'transition',
                                'text-violet-100',
                                'text-lg',
                                'font-bold',
                                'font-[\'Open Sans\']',
                                'leading-normal',
                                activeButton === 'stat' ? 'active' : '',
                            ]"
                            >
                                Статистика
                            </button>
                        </template>
                        <div class="grid sm:grid-cols-2 grid-cols-1 gap-y-10 px-2">
                            <div>
                                <h1
                                    class="text-center text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"
                                >
                                    График с текущим количеством подписчиков
                                </h1>
                                <Line
                                    :data="chartDataSubs"
                                    :options="chartOptionsNoLabels"
                                />
                            </div>
                            <div>
                                <h1
                                    class="text-center text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"
                                >
                                    Охват за 24 часа
                                </h1>
                                <Line
                                    :data="chartDataAvg"
                                    :options="chartOptionsNoLabels"
                                />
                            </div>
                            <div>
                                <h1
                                    class="text-center text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"
                                >
                                    ER% — вовлеченность по взаимодействиям
                                </h1>
                                <Line
                                    :data="chartDataER"
                                    :options="chartOptionsNoLabels"
                                />
                            </div>
                        </div>
                    </n-tab-pane>
                    <n-tab-pane name="review">
                        <template #tab>
                            <button
                                @click.prevent="activeButton = 'review'"
                                :class="[
                                'tab-button',
                                'transition',
                                'text-violet-100',
                                'text-lg',
                                'font-bold',
                                'font-[\'Open Sans\']',
                                'leading-normal',
                                activeButton === 'review' ? 'active' : '',
                            ]"
                            >
                                Отзывы
                            </button>
                        </template>

                        <Reviews :channel-id="channel.id" />
                    </n-tab-pane>
                </n-tabs>
            </div>
    </div>

    </AppLayout>
</template>
<style scoped>
.wrapper {
    position: relative;
    overflow: hidden;

    .card {
        border-radius: 0 75px 75px 75px;
        border: 2px solid #ffffff;
        background: radial-gradient(
            278.82% 137.51% at 1.95% 3.59%,
            rgba(255, 255, 255, 0.4) 0%,
            rgba(81, 63, 255, 0) 100%
        );
        backdrop-filter: blur(15px);
    }
}
.tab-button {
    border-radius: 100px;
    border: 1px solid #6522d9;
    background: transparent;
    padding: 10px 25px;
    width: 100%;
    &:hover {
        background: #301864;
    }
}
.tab-button.active {
    border-radius: 100px;
    border: 1px solid #6522d9;
    background: #301864;
    padding: 10px 25px;
}
</style>
