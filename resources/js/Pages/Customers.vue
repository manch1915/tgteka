<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import SloganBlock from "@/Components/Home/Blocks/SloganBlock.vue";
import MissionBlock from "@/Components/Home/Blocks/MissionBlock.vue";
import BookCard from "@/Components/Home/BookCard.vue";
import HowItWorksBlock from "@/Components/Home/Blocks/HowItWorksBlock.vue";
import PointCard from "@/Components/Home/PointCard.vue";
import InterestChannelsBlock from "@/Components/Home/Blocks/InterestChannelsBlock.vue";
import ChannelCard from "@/Components/Home/ChannelCard.vue";
import IntegrationBlock from "@/Components/Home/Blocks/IntegrationBlock.vue";
import ClientsBlock from "@/Components/Home/Blocks/ClientsBlock.vue";
import ClientsSlider from "@/Components/Home/ClientsSlider.vue";
import ComparisonCard from "@/Components/Home/ComparisonCard.vue";
import IntegrationBackground from "@/Components/Home/Blocks/IntegrationBackground.vue";
import ClientsHeader from "@/Components/Home/ClientsHeader.vue";
import Feedback from "@/Components/Home/Feedback.vue";
import Slider from "@/Components/Home/Slider.vue";
import { SwiperSlide} from 'swiper/vue';
import {ref} from "vue";
import {Head} from "@inertiajs/vue3";
import {Title} from "chart.js";

const headers = ["Регистрируйте аккаунт", "Выберите подходящие каналы в каталоге", "Пополните баланс удобным способом:", "Создайте размещение с картинкой и ссылкой:", "Владелец канала публикует пост и присылает ссылку на проверку:", "Вы можете скачать отчет по итогу размещения:"]
const texts = ["Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc ",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc"];

const compareItems = [
    {
        header: 'Самостоятельное размещение напрямую',
        listText: ['Найти каталог Телеграм-каналов через Google', 'Проверить каналы на живых подписчиков',
            'Написать каждому админу, чтобы узнать стоимость интеграции',
            'Оплатить размещение каждому админу',
            'Написать посты для интеграции',
            'Отправить пост каждому админу',
            'Контролировать выход поста в каждом канале'],
        icons: [
            {
                class: 'blue',
                text: 'ручной процесс',
            },
            {
                class: 'blue',
                text: 'нет гарантий'
            }
        ],
        hasButton: false,
        showSaveTimeMoney: false
    },
    {
        header: 'Автоматическое размещение с TGteka.ru',
        listText:
        [
            'Зарегистрируйтесь на платформе',
            'Выберите подходящие каналы из каталога в один клик или купите размещение под ключ',
            'Создайте интеграцию',
            'Запустите нативную интеграцию',
            'Получите оповещение о каждом размещении'
        ],
        icons: [
            {
                class: 'white',
                text: 'автоматический процесс',
            },
            {
                class: 'white',
                text: 'есть гарантии'
            }
        ],
        hasButton: true,
        showSaveTimeMoney: true
    }
];
const windowWidth = ref(window.innerWidth)

</script>

<template>
    <Head>
        <title>Заказчикам</title>
    </Head>

    <MainLayout>
        <SloganBlock
            header="Нативные размещения в Телеграм: проверенные вручную каналы и боты"
            paragraph="Нативные размещения в Телеграм: проверенные вручную каналы и боты"
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
                    v-for="i in 6"
                    :reversed="(i < 4)"
                    :header="headers[i-1]"
                    :paragraph="texts[i-1]"
                    :number="i"
                    :key="i"
                />
            </template>
        </HowItWorksBlock>
        <InterestChannelsBlock>
            <template v-slot:cards>
                <template v-if="windowWidth <= 1024">
                    <slider :interactive="true" :slides="6">
                        <template v-slot:slider>
                            <template v-for="i in 6" :key="i">
                                <swiper-slide class="pb-20 px-4">
                                    <ChannelCard/>
                                </swiper-slide>
                            </template>
                        </template>
                    </slider>
                </template>
                <template v-else>
                    <ChannelCard v-for="i in 6" :key="i"/>
                </template>
            </template>
        </InterestChannelsBlock>
        <IntegrationBackground/>
        <IntegrationBlock>
            <template v-slot:comparePairs>
                <ComparisonCard v-for=" (item, i) in compareItems" :item="item" :key="i"/>
            </template>
        </IntegrationBlock>
        <ClientsBlock>
            <template v-slot:clients-header>
                <ClientsHeader/>
            </template>
            <template v-slot:slider>
                <clients-slider/>
            </template>
            <template v-slot:clients-footer>
                <feedback/>
            </template>
        </ClientsBlock>
    </MainLayout>
</template>

