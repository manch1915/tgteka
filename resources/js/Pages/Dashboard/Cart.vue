<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import CatalogChannelCard from "@/Components/Dashboard/CatalogChannelCard.vue";
import { computed, onMounted, ref } from "vue";
import { NInput, NSelect, useMessage, useLoadingBar, NDataTable } from "naive-ui";
import {
    selectThemeOverrides,
    textareaThemeOverrides,
} from "@/themeOverrides.js";
import axios from "axios";
import { useMainStore } from "@/stores/main.js";
import { router, Link, Head } from "@inertiajs/vue3";
import { Title } from "chart.js";

const loading = useLoadingBar();

const loadCart = () => {
    return JSON.parse(localStorage.getItem("cart")) || [];
};

const isCartEmpty = computed(() => channels.value.length === 0);

const channels = ref(loadCart());
const userPatterns = ref([]);
const userPattern = ref(null);
const description = ref("");

const store = useMainStore();

const totalParticipants = computed(() =>
    channels.value.reduce(
        (sum, channel) =>
            sum +
            (channel.statistics ? channel.statistics.participants_count : 0),
        0
    )
);
const totalPostReach = computed(() =>
    channels.value.reduce(
        (sum, channel) =>
            sum + (channel.statistics ? channel.statistics.avg_post_reach : 0),
        0
    )
);

const channelCount = computed(() => channels.value.length);
const totalSum = computed(() =>
    channels.value.reduce((sum, channel) => {
        const pricePerUnit = channel[channel.format]
            ? channel[channel.format]
            : 0;
        return sum + pricePerUnit;
    }, 0)
);
const updateChannels = (updatedCart) => {
    channels.value = updatedCart;
};

const formatDate = (timestamp) => {
    const date = new Date(timestamp);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");
    const seconds = String(date.getSeconds()).padStart(2, "0");

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

const orderPosts = () => {
    loading.start();
    const formattedChannels = channels.value.map((channel) => ({
        id: channel.id,
        format: channel.format,
        timestamp: formatDate(channel.timestamp),
        nearFuture: channel.nearFuture,
    }));
    axios
        .post(route("catalog.channels.orderPosts"), {
            channels: formattedChannels,
            pattern_id: userPattern.value,
            description: description.value,
        })
        .then((response) => {
            store.subtractFromUserBalance(response.data);
            localStorage.removeItem("cart");
            loading.finish();
            router.visit(route("placements"));
        })
        .catch((c) => {
            loading.error();
            message.error(c.response.data.message);
        });
};

const message = useMessage();

onMounted(() => {
    axios
        .get(route("user-patterns"))
        .then((response) => {
            const patterns = response.data;
            if (!patterns || patterns.length === 0) {
                message.error(
                    "у вас нет готовых шаблонов пожалуйста перейдите на страницу мои шаблоны и создайте шаблон"
                );
            } else {
                userPatterns.value.push(
                    ...patterns.map((pattern) => ({
                        label: pattern.title,
                        value: pattern.id,
                    }))
                );
            }
        })
        .catch((error) => console.error(error));
});

const formattedTotalSum = computed(() => {
    const value = totalSum.value;
    return new Intl.NumberFormat("ru-RU", {
        style: "currency",
        currency: "RUB",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value);
});

const data = [
    { channels: channelCount.value, subscribers: totalParticipants.value, views: totalPostReach.value, sum: formattedTotalSum.value },
];

const columns = [
    {
        title: "Каналы",
        key: "channels"
    },
    {
        title: "Подписчики",
        key: "subscribers"
    },
    {
        title: "Просмотры",
        key: "views"
    },
    {
        title: "Сумма",
        key: "sum"
    }]

</script>

<template>
    <Head>
        <title>Корзина</title>
    </Head>
    <AppLayout>
        <div class="sm:py-20 py-4 text-center">
            <h1
                class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10"
            >
                Корзина
            </h1>
        </div>
        <div class="grid lg:grid-cols-[3fr_1fr] grid-cols-1 gap-x-4 px-2">
            <div class="sm:order-1 order-2">
                <div class="channels">
                    <div class="flex flex-col gap-y-4 lg:mt-[10px] mt-8">
                        <template
                            v-if="!isCartEmpty"
                            v-for="channel in channels"
                            :key="channel.id"
                        >
                            <CatalogChannelCard
                                @cart-updated="updateChannels"
                                is-cart
                                :channel="channel"
                                :timestamp="channel.timestamp"
                            />
                        </template>
                        <div
                            v-else
                            class="flex justify-center flex-col items-center"
                        >
                            <p
                                class="text-violet-100 text-center text-2xl font-bold font-['Open Sans'] leading-10"
                            >
                                В корзине пусто.
                            </p>
                            <div class="my-12">
                                <Link :href="route('catalog.channels.index')">
                                    <button
                                        class="text-violet-100 px-6 py-4 btn_gradient-purple transition hover:bg-purple-800 rounded-full"
                                    >
                                        Перейти в каталог
                                    </button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sm:order-2 order-1 sm:p-0 p-2">
                <div class="my-2">
                    <n-select
                        :disabled="isCartEmpty"
                        placeholder="Шаблоны"
                        v-model:value="userPattern"
                        :options="userPatterns"
                        :theme-overrides="selectThemeOverrides"
                    />
                </div>
                <div class="my-2">
                    <n-input
                        :autosize="{ minRows: 4, maxRows: 10 }"
                        :disabled="isCartEmpty"
                        type="textarea"
                        maxlength="300"
                        show-count
                        v-model:value="description"
                        :theme-overrides="textareaThemeOverrides"
                        placeholder="Требования к заказу"
                    />
                </div>
                <n-data-table :columns="columns" :data="data"/>
                <button
                    :disabled="isCartEmpty"
                    @click.prevent="orderPosts"
                    class="w-full my-4 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal transition btn_gradient-purple hover:bg-purple-800 rounded-3xl py-2"
                >
                    Купить размещение
                </button>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
.channels {
    @media screen and (max-width: 640px) {
        padding: 0 15px;
    }
    min-width: 0;
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
