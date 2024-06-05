<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import MissionBlock from "@/Components/Home/Blocks/MissionBlock.vue";
import BookCard from "@/Components/Home/BookCard.vue";
import PointCard from "@/Components/Home/PointCard.vue";
import HowItWorksBlock from "@/Components/Home/Blocks/HowItWorksBlock.vue";
import IntegrationBackground from "@/Components/Home/Blocks/IntegrationBackground.vue";
import ClientsBlock from "@/Components/Home/Blocks/ClientsBlock.vue";
import ClientsSlider from "@/Components/Home/ClientsSlider.vue";
import ClientsHeader from "@/Components/Home/ClientsHeader.vue";
import Faq from "@/Components/Home/Faq.vue";

import TechnicalIssues from "@/Components/Home/Blocks/TechnicalIssues.vue";
import SloganBlock from "@/Components/Home/Blocks/SloganBlock.vue";
import Slider from "@/Components/Home/Slider.vue";
import {computed, onMounted, onUnmounted, ref} from "vue";
import { Head } from "@inertiajs/vue3";
import { Title } from "chart.js";
import GoUp from "@/Components/Home/GoUp.vue";
import { useWindowWidth } from '@/utilities/windowWidth.js'

const headers = [
    "Добавляете канал в каталог",
    "Получаете заказы и размещаете посты",
    "Получаете оплату",
];
const texts = [
    "В среднем модерация занимает 24 часа ",
    "Оповещения о новых размещениях приходят на E-mail и в Telegram-бота",
    "Вы сможете выводить средства от 1 000 рублей три раза в неделю",
];

const { windowWidth } = useWindowWidth()

const spaceBetween = computed(() => {
    if (windowWidth.value <= 425) {
        return 20;
    } else if (windowWidth.value < 609) {
        return 10;
    } else {
        return 100;
    }
});

</script>

<template>
    <Head>
        <title>Владельцу канала</title>
    </Head>

    <MainLayout>
        <SloganBlock
            header="Постоянный поток заказов на размещения в Telegram-канале или боте"
            paragraph="Свыше 70 000 заказчиков готовы платить за вашу Telegram-аудиторию"
        />
        <MissionBlock>
            <template v-slot:cards>
                <template v-if="windowWidth <= 1024">
                    <slider :interactive="false" :space-between="spaceBetween" slides-per-view="auto" class="my-14" height-setting>
                        <template v-slot:slider>
                            <template v-for="i in 3" :key="i">
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
        <HowItWorksBlock :short-line="true" class="HowItWorksBlock">
            <template v-slot:points>
                <point-card
                    v-for="i in 3"
                    :reversed="true"
                    :header="headers[i - 1]"
                    :paragraph="texts[i - 1]"
                    :number="i"
                    :key="i"
                />
            </template>
        </HowItWorksBlock>

        <div class="py-12 md:pt-24">
            <IntegrationBackground />
        </div>

        <ClientsBlock>
            <template v-slot:clients-header>
                <ClientsHeader />
            </template>
            <template v-slot:slider>
                <clients-slider />
            </template>
            <template v-slot:clients-footer> </template>
        </ClientsBlock>

        <faq />

        <TechnicalIssues />
    </MainLayout>
    <GoUp/>
</template>

<style scoped lang="scss"></style>
