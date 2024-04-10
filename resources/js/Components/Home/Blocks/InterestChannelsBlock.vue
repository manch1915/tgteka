<template>
    <div class="clients_mid">
        <div class="curvedside">
            <div class="curvedside__mask"></div>
            <div class="edge"></div>
            <div class="curvedside__base">
                <div class="curvedside__content">
                    <div class="interestChannels interestChannels_mid px-4">
                        <div class="w-full">
                            <div class="container mx-auto">
                                <div
                                    class="interestChannels__header sm:mt-0 lg:mb-24 mb-10"
                                >
                                    <h1
                                        class="text-center text-violet-100 lg:text-4xl text-3xl font-bold font-['Open Sans'] leading-10"
                                    >
                                        Интересные каналы из нашего каталога
                                    </h1>
                                </div>
                                <div class="interestChannels__categories">
                                    <div
                                        class="lg:flex justify-between hidden gap-2"
                                    >
                                        <div
                                            class="flex flex-col gap-6 self-start"
                                        >
                                            <div
                                                class="text-violet-100 text-xl font-normal font-['Open Sans']"
                                            >
                                                Все каналы в нашем каталоге
                                                отсортированы <br />
                                                по категориям, вот некоторые из
                                                них
                                            </div>
                                            <div>
                                                <div
                                                    class="interestChannels__categories-button"
                                                >
                                                    <div
                                                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                                                    >
                                                        Посмотреть остальные
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="InterestChannelsCards grid h-auto grid-cols-2 lg:grid-cols-2 justify-center sm:gap-8 gap-1"
                                        >
                                            <InterestChannelsCard
                                                p="Криптовалюта и Блокчейн"
                                            />
                                            <InterestChannelsCard
                                                p="Кулинария"
                                            />
                                            <InterestChannelsCard p="Спорт" />
                                            <InterestChannelsCard
                                                p="Путешествия"
                                            />
                                        </div>
                                    </div>
                                    <div
                                        :class="
                                            windowWidth <= 1024
                                                ? 'channels w-full grid grid-cols-1 justify-between gap-9 relative'
                                                : 'channels grid  2xl:grid-cols-3 grid-cols-2 justify-between gap-9 mt-20 relative'
                                        "
                                    >
                                        <div class="fon-radial-purple"></div>
                                        <div class="fon-radial-blue"></div>
                                        <slot name="cards"> </slot>
                                    </div>
                                    <div
                                        class="text-center py-20 pb-40 lg:block hidden"
                                    >
                                        <div class="gotocatalog transition-all">
                                            <button
                                                class="transition-all text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                                            >
                                                Перейти в полный каталог
                                            </button>
                                        </div>
                                    </div>
                                    <div
                                        class="block justify-between lg:hidden"
                                    >
                                        <div class="flex flex-col gap-6">
                                            <div
                                                class="text-center text-violet-100 text-lg font-normal font-['Open Sans'] leading-tight"
                                            >
                                                Все каналы в нашем каталоге
                                                отсортированы по категориям, вот
                                                некоторые из них
                                            </div>
                                        </div>
                                        <div class="flex justify-between gap-4">
                                            <slider
                                                :interactive="false"
                                                slides="4"
                                                :freemode="true"
                                                :slidesPerView="2"
                                            >
                                                <template v-slot:slider>
                                                    <swiper-slide
                                                        v-for="n in 4"
                                                        :key="n"
                                                    >
                                                        <div
                                                            class="flex justify-center pt-10 pb-14 lg:py-14"
                                                        >
                                                            <InterestChannelsCard
                                                                p="Криптовалюта и Блокчейн"
                                                            />
                                                        </div>
                                                    </swiper-slide>
                                                </template>
                                            </slider>
                                        </div>
                                        <div class="">
                                            <button
                                                class="text-violet-100 text-lg mx-auto font-bold font-['Open Sans'] leading-normal interestChannels__categories-button"
                                            >
                                                Посмотреть остальные
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <IntegrationBackground class="margin-top" />
                    <IntegrationBlock>
                        <template v-slot:comparePairs>
                            <ComparisonCard
                                :background="i"
                                v-for="(item, i) in compareItems"
                                :item="item"
                                :key="i"
                            />
                        </template>
                    </IntegrationBlock>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import InterestChannelsCard from "@/Components/Home/InterestChannelsCard.vue";
import IntegrationBlock from "@/Components/Home/Blocks/IntegrationBlock.vue";
import ComparisonCard from "@/Components/Home/ComparisonCard.vue";
import IntegrationBackground from "@/Components/Home/Blocks/IntegrationBackground.vue";
import { onMounted, onUnmounted, ref } from "vue";
import Slider from "@/Components/Home/Slider.vue";
import { SwiperSlide } from "swiper/vue";
const compareItems = [
    {
        header: "Самостоятельное размещение напрямую",
        listText: [
            "Найти каталог Телеграм-каналов через Google",
            "Проверить каналы на живых подписчиков",
            "Написать каждому админу, чтобы узнать стоимость интеграции",
            "Оплатить размещение каждому админу",
            "Написать посты для интеграции",
            "Отправить пост каждому админу",
            "Контролировать выход поста в каждом канале",
        ],
        icons: [
            {
                class: "blue",
                text: "ручной процесс",
            },
            {
                class: "blue",
                text: "нет гарантий",
            },
        ],
        hasButton: false,
        showSaveTimeMoney: false,
    },
    {
        header: "Автоматическое размещение с TGteka.ru",
        listText: [
            "Зарегистрируйтесь на платформе",
            "Выберите подходящие каналы из каталога в один клик или купите размещение под ключ",
            "Создайте интеграцию",
            "Запустите нативную интеграцию",
            "Получите оповещение о каждом размещении",
        ],
        icons: [
            {
                class: "white",
                text: "автоматически",
            },
            {
                class: "white",
                text: "гарантия размещения",
            },
        ],
        hasButton: true,
        showSaveTimeMoney: true,
    },
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
</script>
<style scoped lang="scss">
$bg: linear-gradient(160deg, rgba(55, 50, 189, 1) 11%, rgba(7, 12, 41, 1) 60%);
$mask: "/images/background-Vector-1.svg";
$maskHeight: 23vw; /* адаптив */
$maskPos: "top";
$minHeight: 300px;
$offset: 0px;
.clients_mid {
    height: 100%;
    margin-top: 100px;
    @media (max-width: 640px) {
        margin-top: 100px;
    }
    .curvedside {
        position: relative;
        width: 100%;
        z-index: 2;

        .curvedside__mask {
            position: absolute;
            pointer-events: none;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            mask-image: url($mask);
            mask-repeat: no-repeat;
            mask-position: #{$maskPos} left;
            z-index: -1;
            mask-size: 100%, $maskHeight, contain;
            /*mask-repeat: repeat-x*/

            background: $bg;
            @media (max-width: 640px) {
                mask-size: 100%, 25vw, cover;
                width: 1000px;
            }
        }

        .curvedside__base {
            min-height: calc($minHeight + $offset);
            width: 100%;
            margin-top: 50px;

            background: $bg;

            background-clip: content-box;
            padding-#{$maskPos}: $maskHeight;
            @media (max-width: 640px) {
                padding-#{$maskPos}: 25vw;
            }
        }

        .curvedside__content {
            min-height: 300px;
            position: relative;

            margin-top: $offset;
        }

        .edge {
            position: absolute;
            pointer-events: none;
            top: 0;
            left: 0;
            width: 100%;
            height: $maskHeight;
            background-image: url("/images/vector-volna.svg");
            background-size: contain;
            background-repeat: no-repeat;
            @media (max-width: 640px) {
                width: 1000px;
                height: 27vw;
                background-size: cover;
            }
        }
    }
}
.fon-radial-purple,
.fon-radial-blue {
    position: absolute;
    pointer-events: none;
    display: block;
    left: -15%;
    bottom: 0;
    height: 500px;
    width: 900px;
    z-index: 0;
    background: radial-gradient(
        50% 50% at 50% 50%,
        rgba(179, 48, 150, 0.73) 0%,
        rgba(173, 55, 154, 0) 100%
    );
    background-size: 100% 100%;
    background-repeat: no-repeat;
    background-position: center;
    filter: blur(50px);
    z-index: 0;
    @media (max-width: 1024px) {
        bottom: -25%;
        left: auto;
    }
}
.fon-radial-blue {
    left: auto;
    right: -25%;
    top: 35%;
    left: auto;
    height: 300px;
    width: 500px;
    filter: blur(100px);
    background: radial-gradient(
        50% 50% at 50% 50%,
        rgba(141, 112, 254, 1) 0%,
        rgba(80, 55, 173, 0) 100%
    );
    @media (max-width: 1024px) {
        display: none;
    }
}
// Define Sass variables for breakpoints and corresponding heights
$breakpoints: (
    "1920": 20,
    "1600": 25,
    "1440": 28,
    "1366": 30,
    "1280": 32,
    "1024": 35,
    "960": 37,
    "800": 40,
    "768": 42,
    "600": 50,
    "480": 60,
    "425": 65,
    "375": 70,
    "360": 75,
    "320": 80,
);
$breakpoints_bottom: (
    "1670": 70,
    "1570": 80,
    "1480": 85,
    "1440": 90,
    "1366": 95,
    "1280": 110,
    "1160": 120,
    "1024": 120,
    "960": 120,
    "840": 130,
    "780": 140,
    "720": 150,
    "670": 160,
    "540": 170,
    "510": 180,
    "470": 200,
    "450": 220,
    "410": 240,
    "380": 250,
);
.InterestChannelsCards {
    grid-template-columns: repeat(4, 225px);
    @media screen and (max-width: 1550px) {
        grid-template-columns: repeat(4, 200px);
    }
    @media screen and (max-width: 1380px) {
        grid-template-columns: repeat(2, 1fr);
    }
}
.interestChannels_top {
    background: url("/images/interestChannelsBackground.svg") no-repeat center;
    background-size: cover;
    background-position-y: calc(200px - 50vw);
    width: 100%;
    height: calc(#{map-get($breakpoints, "1440")}vw); // Initial height

    // Calculate height based on viewport width
    @each $bp, $height in $breakpoints {
        @media (max-width: #{$bp}px) {
            height: calc(#{$height}vw);
        }
    }
}

.interestChannels_bottom {
    background: url("/images/interestChannelsBackground.svg") no-repeat center;
    background-size: cover;
    background-position-y: calc(-137px - 50vw);
    width: 100%;
    height: calc(#{map-get($breakpoints_bottom, "1440")}vw); // Initial height

    // Calculate height based on viewport width
    @each $bp, $height in $breakpoints_bottom {
        @media (max-width: #{$bp}px) {
            height: calc(#{$height}vw);
        }
    }
}
.interestChannels {
    .interestChannels__categories-button {
        display: flex;
        padding: 16px 25px;

        width: fit-content;

        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: #171961;
        box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25) inset;
    }

    .interestChannels__header {
        position: relative;
        width: 100%;

        &:before {
            content: "";
            position: absolute;
            left: 10%;
            bottom: 50%;
            transform: translateY(50%);
            width: 500px;
            height: 180px;
            background: url("/images/plane.svg") no-repeat center;
            @media screen and (max-width: 1280px) {
                left: 5%;
                bottom: 70%;
            }
            @media screen and (max-width: 1024px) {
                left: 0%;
                bottom: 70%;
            }
            @media screen and (max-width: 767px) {
                display: none;
            }
        }
    }
}

.gotocatalog {
    border-radius: 100px;
    display: inline-block;
    button {
        padding: 16px 25px;
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: #171961;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
    }
    &:hover {
        button {
            box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0.25) inset;
        }
        box-shadow: 0px 0px 17px 0px #7375ae;
    }
}
</style>
