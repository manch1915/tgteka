<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination } from "swiper/modules";
import "swiper/css";
import "swiper/css/pagination";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import ReviewCard from "@/Components/Home/ReviewCard.vue";

let activeIndex = ref(1);
const onSlideChange = (swiper) => {
    activeIndex.value = swiper.realIndex + 1;
};
let mySwiper = ref();

// Swiper slide data

let bullets = ref("");

const renderBullet = (index, className) => {
    return `<div class="${className}" @click="goToSlide(${index})"></div>`;
};

const width = ref(window.innerWidth);

let reviewCardsCountPerSlide = computed(() => {
    return width.value <= 640 ? 1 : 4;
});
const pagination = {
    el: ".pagination-mod",
    clickable: true,
    renderBullet: function (index, className) {
        return '<span class="!mt-1 ' + className + '"></span>';
    },
};

const goToSlide = (index) => {
    if (mySwiper.value) {
        mySwiper.value.swiper.slideTo(index);
    }
};

watch(
    activeIndex,
    (newVal) => {
        bullets = '<div class="pagination_el"></div>'.repeat(newVal);
    },
    { immediate: true }
);
const modules = [Navigation, Pagination];

const updateWindowAndSwiper = () => {
    width.value = window.innerWidth;

    // If swiper component is already initialized
    if (mySwiper.value) {
        mySwiper.value.update(); // Refresh the swiper
    }
};

onMounted(() => {
    // Add the updateWindowAndSwiper function as a window resize listener
    window.addEventListener("resize", updateWindowAndSwiper);
});

onUnmounted(() => {
    // Remove listener when component is unmounted
    window.removeEventListener("resize", updateWindowAndSwiper);
});
</script>

<template>
    <div class="main px-4">
        <div class="interactive pb-4">
            <div class="buttons flex gap-3 items-center">
                <div class="arrow arrow_left">
                    <img src="/images/arrow.svg" alt="arrow" />
                </div>
                <div
                    class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed"
                >
                    {{ activeIndex }} / 3
                </div>
                <div class="arrow arrow_right">
                    <img src="/images/arrow.svg" alt="arrow" />
                </div>
            </div>
        </div>
        <div class="w-full">
            <swiper
                :autoHeight="true"
                :navigation="{ prevEl: '.arrow_left', nextEl: '.arrow_right' }"
                :pagination="pagination"
                @slide-change="onSlideChange"
                :modules="modules"
                loop
                slides-per-view="1"
                сlass="radial-fon-pink"
            >
                <swiper-slide
                    class="sm:!grid sm:!grid-cols-2 sm:!gap-4 sm:p-0 p-2"
                >
                    <ReviewCard v-for="i in reviewCardsCountPerSlide" />
                </swiper-slide>
                <swiper-slide
                    class="sm:!grid sm:!grid-cols-2 sm:!gap-4 sm:p-0 p-2"
                >
                    <ReviewCard v-for="i in reviewCardsCountPerSlide" />
                </swiper-slide>
                <swiper-slide
                    class="sm:!grid sm:!grid-cols-2 sm:!gap-4 sm:p-0 p-2"
                >
                    <ReviewCard v-for="i in reviewCardsCountPerSlide" />
                </swiper-slide>
            </swiper>
        </div>
        <div
            class="pagination-mod flex items-center content-center justify-center sm:mt-11 mt-4"
        ></div>
        <div class="review sm:pt-16 pt-12 flex justify-center">
            <button
                class="px-6 py-4 btn_gradient-purple transition hover:bg-purple-800 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
            >
                Добавить отзыв
            </button>
        </div>
    </div>
</template>

<style scoped lang="scss">
.w-full {
    position: relative;
    &::before {
        content: "";
        position: absolute;
        pointer-events: none;
        display: block;
        left: 50%;
        top: 50%;
        height: 100%;
        width: 100vw;
        transform: translate(-50%, -50%);
        z-index: 0;
        background: radial-gradient(
            50% 50% at 50% 50%,

            rgba(179, 48, 150, 0.73) 0%,
            rgba(173, 55, 154, 0) 100%
        );

        background-repeat: no-repeat;
        background-position: center;
        filter: blur(100px);
    }
}
.block {
    padding: 60px 60px 45px 80px;
    border-radius: 0px 40px 40px 40px;

    background: radial-gradient(
        278.82% 137.51% at 1.95% 3.59%,
        rgba(255, 255, 255, 0.26) 0%,
        rgba(255, 255, 255, 0) 100%
    );

    &::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1.5px;
        background: rgb(24, 25, 94);
        pointer-events: none;
        background: linear-gradient(
                0deg,
                rgba(24, 25, 94, 1) 0%,
                rgba(21, 21, 21, 0) 100%
            ),
            linear-gradient(
                0deg,
                rgba(89, 61, 239, 1) 0%,
                rgba(89, 61, 239, 1) 100%
            ),
            linear-gradient(
                0deg,
                rgba(255, 255, 255, 1) 0%,
                rgba(255, 255, 255, 0) 100%
            );
        -webkit-mask: linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
    }
    @media screen and (max-width: 640px) {
        padding: 30px 15px 25px 50px;
    }
    backdrop-filter: blur(21px);

    .review {
        position: relative;

        &:before {
            content: "“";
            position: absolute;
            color: var(--White, #eae0ff);
            font-family: Montserrat, serif;
            font-size: 40px;
            font-style: normal;
            font-weight: 900;
            line-height: 140%; /* 56px */

            left: -30px;
            top: -20px;
        }

        &:after {
            content: "”";
            position: absolute;
            font-family: Montserrat, serif;
            font-size: 40px;
            font-style: normal;
            font-weight: 900;
            line-height: 140%; /* 56px */
            right: -10px;
        }
    }
}

.interactive {
    width: 100%;
    padding-bottom: 30px;

    .buttons {
        margin-left: auto;
        width: fit-content;
        .arrow {
            width: 30px;
            height: 30px;

            &:hover {
                cursor: pointer;

                img {
                    filter: brightness(12);
                }
            }
        }

        .arrow_right {
            transform: rotate(180deg);
        }
    }
}
</style>
