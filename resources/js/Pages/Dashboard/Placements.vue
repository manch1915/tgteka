<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {onMounted, ref, watch} from "vue";
import PlacementCard from "@/Components/Dashboard/PlacementCard.vue";
import { NDatePicker, NSlider, useLoadingBar } from "naive-ui";
import { Head, Link } from "@inertiajs/vue3";
import CustomPagination from "@/Components/Dashboard/CustomPagination.vue";
import { useWindowWidth } from '@/utilities/windowWidth.js'
import {useConversationsStore} from "@/stores/ConversationsStore.js";

const placements = ref([]);
const activeSortButton = ref("");
const value = ref([0, 1500]);
const range = ref([118313526e4, Date.now()]);
const isLoading = ref(true);
const maxPrice = ref(1500);
const loading = useLoadingBar();
const hasAnyOrder = ref(false);

const store = useConversationsStore();
store.getConversations();

const SORT_DATA = [
    {
        title: "в ожидании",
        value: "pending",
    },
    {
        title: "принятый",
        value: "accepted",
    },
    {
        title: "отклоненный",
        value: "declined",
    },
    {
        title: "проверить",
        value: "check",
    },
    {
        title: "завершенный",
        value: "finished",
    },
];

const formatTooltip = (value) => `${value} руб.`;

let debounceTimeoutId;
const getPlacements = async (page = 1) => {
    clearTimeout(debounceTimeoutId);

    debounceTimeoutId = setTimeout(async () => {
        loading.start();
        isLoading.value = true;
        let url =
            route("placements.get") +
            `?page=${page}` +
            `&status=${activeSortButton.value}` +
            `&minPrice=${value.value[0]}` +
            `&maxPrice=${value.value[1]}` +
            `&startDate=${new Date(range.value[0]).toJSON()}` +
            `&endDate=${new Date(range.value[1]).toJSON()}`;

        await axios
            .get(url)
            .then((response) => {
                placements.value = response.data.orders;
                maxPrice.value = response.data.max_price;
                hasAnyOrder.value = response.data.hasAnyOrder;
            })
            .catch((err) => console.log(err))
            .finally(() => {
                loading.finish();
                isLoading.value = false;
            });
    }, 500);
};
onMounted(() => getPlacements());

const handleOrderAccepted = () => {
    getPlacements();
};

watch([activeSortButton, value, range], () => {
    getPlacements();
});

const windowWidth = useWindowWidth()

</script>

<template>
    <Head>
        <title>Мои размещения</title>
    </Head>
    <AppLayout>
        <div class="sm:py-20 py-4 text-center px-2">
            <h1
                class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10"
            >
                Мои размещения
            </h1>
            <div
                v-if="hasAnyOrder"
                class="flex sm:flex-row flex-col justify-between items-center gap-8 mt-4 sm:p-0 p-2"
            >
                <div
                    class="flex flex-wrap gap-x-2 sm:justify-start justify-around gap-2"
                >
                    <template v-for="sort in SORT_DATA">
                        <button
                            :class="{
                                background: activeSortButton === sort.title,
                            }"
                            @click.prevent="activeSortButton = sort.value"
                            class="transition px-5 py-1 hover:bg-violet-950 rounded-full border border-violet-700 justify-start items-start text-violet-100 text-sm font-bold font-['Open Sans']"
                        >
                            {{ sort.title }}
                        </button>
                    </template>
                </div>
                <div class="w-full">
                    <n-slider
                        v-model:value="value"
                        range
                        :step="1"
                        :max="maxPrice"
                        :format-tooltip="formatTooltip"
                    />
                    <div class="flex justify-between">
                        <p
                            class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-10"
                        >
                            {{ value[0] }} руб.
                        </p>
                        <p
                            class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-10"
                        >
                            -
                        </p>
                        <p
                            class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-10"
                        >
                            {{ value[1] }} руб.
                        </p>
                    </div>
                    <n-date-picker
                        v-model:value="range"
                        type="daterange"
                        clearable
                    />
                </div>
            </div>
            <div class="flex flex-col gap-y-4 mt-8 px-2">
                <template
                    v-if="
                        !isLoading &&
                        placements &&
                        placements.data &&
                        placements.data.length > 0
                    "
                    v-for="(order, index) in placements.data"
                    :key="index"
                >
                    <placement-card
                        :order="order"
                        @orderAccepted="handleOrderAccepted"
                    />
                </template>
                <div
                    v-else-if="
                        !isLoading &&
                        placements &&
                        placements.data &&
                        placements.data.length === 0
                    "
                >
                    <p
                        class="text-violet-100 text-center text-2xl font-bold font-['Open Sans'] leading-10"
                    >
                        У вас отсутствуют размещения.
                    </p>
                    <div class="mt-12">
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
            <div class="flex justify-center my-2">
                <CustomPagination @pagination-change-page="getPlacements" :data="placements"/>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">
.background {
    --tw-bg-opacity: 1;
    background-color: rgb(46 16 101 / var(--tw-bg-opacity));
}
</style>
