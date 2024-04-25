<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import SloganBlock from "@/Components/Home/Blocks/SloganBlock.vue";
import MissionBlock from "@/Components/Home/Blocks/MissionBlock.vue";
import BookCard from "@/Components/Home/BookCard.vue";
import HowItWorksBlock from "@/Components/Home/Blocks/HowItWorksBlock.vue";
import PointCard from "@/Components/Home/PointCard.vue";
import InterestChannelsBlock from "@/Components/Home/Blocks/InterestChannelsBlock.vue";
import ChannelCard from "@/Components/Home/ChannelCard.vue";

import ClientsBlock from "@/Components/Home/Blocks/ClientsBlock.vue";
import ClientsSlider from "@/Components/Home/ClientsSlider.vue";

import ClientsHeader from "@/Components/Home/ClientsHeader.vue";
import Feedback from "@/Components/Home/Feedback.vue";
import Slider from "@/Components/Home/Slider.vue";
import {computed, onMounted, onUnmounted, ref} from "vue";
import { Head } from "@inertiajs/vue3";
import { Title } from "chart.js";
import GoUp from "@/Components/Home/GoUp.vue";

const headers = [
    "Регистрируйте аккаунт",
    "Выберите подходящие каналы в каталоге",
    "Пополните баланс удобным способом:",
    "Создайте размещение с картинкой и ссылкой:",
    "Владелец канала публикует пост и присылает ссылку на проверку:",
    "Вы можете скачать отчет по итогу размещения:",
];
const texts = [
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc ",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
];

const windowWidth = ref(window.innerWidth);
const updateWidth = () => {
    windowWidth.value = window.innerWidth;
};

onMounted(() => {
    // Add the updateWidth function as a window resize listener
    window.addEventListener("resize", updateWidth);
});

onUnmounted(() => {
    // Remove listener when component is unmounted
    window.removeEventListener("resize", updateWidth);
});

const spaceBetween = computed(() => {
    if (windowWidth.value <= 425) {
        return 10;
    } else if (windowWidth.value < 609) {
        return 10;
    } else {
        return 100;
    }
});
const fetchChannels = async () => {
    try {
        const response = await axios.get(route('best-channels.get'));
        return response.data;
    } catch (error) {
        console.error('Error fetching channels:', error);
        return [];
    }
};

// Define a reactive ref to hold the channels data
const channelsData = ref([]);

// Fetch channels data when the component mounts
onMounted(async () => {
    channelsData.value = await fetchChannels();
});
</script>

<template>
    <Head>
        <title>Заказчикам</title>
    </Head>

    <MainLayout>
        <SloganBlock
            header="Нативные размещения в&nbsp;Телеграм: проверенные вручную каналы&nbsp;и&nbsp;боты"
            paragraph="Нативные размещения в&nbsp;Телеграм: проверенные вручную каналы&nbsp;и&nbsp;боты"
        />
        <MissionBlock>
            <template v-slot:cards>
                <template v-if="windowWidth <= 1024">
                    <slider :interactive="false" :space-between="spaceBetween" slides-per-view="auto" class="my-14" height-setting>
                        <template v-slot:slider>
                            <template v-for="i in 4" :key="i">
                                <div class="keen-slider__slide" style="height: 105%" :style="{ 'min-width': windowWidth <= 609 ? '280px' : '410px', 'max-width': windowWidth <= 609 ? '280px' : '410px' }">
                                    <book-card class="flex justify-center"/>
                                </div>
                            </template>
                        </template>
                    </slider>
                </template>
                <template v-else>
                    <book-card v-for="i in 3" />
                </template>
            </template>
        </MissionBlock>

        <HowItWorksBlock>
            <template v-slot:points>
                <point-card
                    v-for="i in 6"
                    :reversed="i < 4"
                    :header="headers[i - 1]"
                    :paragraph="texts[i - 1]"
                    :number="i"
                    :key="i"
                />
            </template>
        </HowItWorksBlock>
        <InterestChannelsBlock>
            <template v-slot:cards>
                <template v-if="windowWidth <= 1024">
                    <slider :interactive="true" :slides-per-view="1">
                        <template v-slot:slider>
                            <template v-for="(channel, index) in channelsData" :key="index">
                                <div class="keen-slider__slide">
                                    <ChannelCard :key="index" :channel="channel" />
                                </div>
                            </template>
                        </template>
                    </slider>
                </template>
                <template v-else>
                    <ChannelCard v-for="(channel, index) in channelsData" :key="index" :channel="channel" />
                </template>
            </template>
        </InterestChannelsBlock>

        <ClientsBlock>
            <template v-slot:clients-header>
                <ClientsHeader />
            </template>
            <template v-slot:slider>
                <clients-slider />
            </template>
            <template v-slot:clients-footer>
                <feedback />
            </template>
        </ClientsBlock>
    </MainLayout>
    <GoUp/>
</template>
