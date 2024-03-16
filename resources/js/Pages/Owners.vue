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
import {SwiperSlide} from "swiper/vue";
import {onMounted, onUnmounted, ref} from "vue";
import {Head} from "@inertiajs/vue3";
import {Title} from "chart.js";

const headers = ["Добавляете канал в каталог", "Получаете заказы и размещаете посты", "Получаете оплату"]
const texts = ["В среднем модерация занимает 24 часа ", "Оповещения о новых размещениях приходят на E-mail и в Telegram-бота",
    "Вы сможете выводить средства от 1 000 рублей три раза в неделю"];
const windowWidth = ref(window.innerWidth)

const updateWidth = () => {
    windowWidth.value = window.innerWidth;
};

onMounted(() => {
    // Add the updateWidth function as a window resize listener
    window.addEventListener('resize', updateWidth);
});

onUnmounted(() => {
    // Remove listener when component is unmounted
    window.removeEventListener('resize', updateWidth);
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
                <template v-if="windowWidth <= 640">
                    <slider :interactive="false">
                        <template v-slot:slider>
                            <template v-for="i in 3" :key="i">
                                <swiper-slide class="py-20">
                                    <book-card class="flex justify-center"/>
                                </swiper-slide>
                            </template>
                        </template>
                    </slider>
                </template>
                <template v-else>
                    <book-card v-for="i in 3"/>
                </template>
            </template>
        </MissionBlock>
        <HowItWorksBlock>
            <template v-slot:points>
                <point-card
                    v-for="i in 3"
                    :reversed="false"
                    :header="headers[i-1]"
                    :paragraph="texts[i-1]"
                    :number="i"
                    :key="i"
                />
            </template>
        </HowItWorksBlock>
        <div class="py-12 md:pt-24">
            <IntegrationBackground/>
        </div>
        <ClientsBlock>
            <template v-slot:clients-header>
                <ClientsHeader/>
            </template>
            <template v-slot:slider>
                <clients-slider/>
            </template>
            <template v-slot:clients-footer>
            </template>

        </ClientsBlock>
        <faq/>

        <TechnicalIssues/>
    </MainLayout>
</template>

<style scoped lang="scss">

</style>
