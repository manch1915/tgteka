<script setup>
import {Swiper, SwiperSlide} from 'swiper/vue';
import {Navigation, Pagination} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import {computed, onMounted, onUnmounted, ref, watch} from "vue";
import ReviewCard from "@/Components/Home/ReviewCard.vue";

let activeIndex = ref(1);
const onSlideChange = (swiper) => {
    activeIndex.value = swiper.realIndex + 1;
};
let mySwiper = ref();

// Swiper slide data

let bullets = ref('');

const renderBullet = (index, className) => {
    return `<div class="${className}" @click="goToSlide(${index})"></div>`;
};

const width = ref(window.innerWidth)

let reviewCardsCountPerSlide = computed(() => {

    return width.value <= 640 ? 1 : 4;
});
const pagination = {
    el: '.pagination-mod',
    clickable: true,
    renderBullet: function (index, className) {
        return '<span class="!mt-1 ' + className + '"></span>';
    },
}

const goToSlide = (index) => {
    if (mySwiper.value) {
        mySwiper.value.swiper.slideTo(index);
    }
};

watch(activeIndex, (newVal) => {

    bullets = '<div class="pagination_el"></div>'.repeat(newVal);
}, { immediate: true });
const modules = [Navigation, Pagination]

const updateWindowAndSwiper = () => {
    width.value = window.innerWidth;

    // If swiper component is already initialized
    if (mySwiper.value) {
        mySwiper.value.update(); // Refresh the swiper
    }
};

onMounted(() => {
    // Add the updateWindowAndSwiper function as a window resize listener
    window.addEventListener('resize', updateWindowAndSwiper);
});

onUnmounted(() => {
    // Remove listener when component is unmounted
    window.removeEventListener('resize', updateWindowAndSwiper);
});
</script>

<template>
    <div class="main">
        <div class="interactive pb-4">
            <div class="buttons flex items-center ">
                <div class="arrow arrow_left">
                    <img src="/images/arrow.svg" alt="arrow">
                </div>
                <div class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">{{activeIndex}}/3</div>
                <div class="arrow arrow_right">
                    <img src="/images/arrow.svg" alt="arrow">
                </div>
            </div>
        </div>
        <div class="w-full">
            <swiper :autoHeight="true" :navigation="{ prevEl: '.arrow_left', nextEl: '.arrow_right'}"  :pagination="pagination"  @slide-change="onSlideChange"  :modules="modules" loop slides-per-view="1">
                <swiper-slide class="sm:!grid sm:!grid-cols-2 sm:!gap-4 sm:p-0 p-2">
                    <ReviewCard v-for="i in reviewCardsCountPerSlide"/>
                </swiper-slide>
                <swiper-slide class="sm:!grid sm:!grid-cols-2 sm:!gap-4 sm:p-0 p-2">
                    <ReviewCard v-for="i in reviewCardsCountPerSlide"/>
                </swiper-slide>
                <swiper-slide class="sm:!grid sm:!grid-cols-2 sm:!gap-4 sm:p-0 p-2">
                    <ReviewCard v-for="i in reviewCardsCountPerSlide"/>
                </swiper-slide>
            </swiper>
        </div>
        <div class="pagination-mod flex items-center content-center justify-center gap-6 mt-4"></div>
        <div class="review pt-12 flex justify-center">
            <button class="px-6 py-4 bg-purple-600 transition hover:bg-purple-800 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                Добавить отзыв
            </button>
        </div>
    </div>
</template>

<style scoped lang="scss">
.block {
    padding: 50px 80px;
    border-radius: 1px 40px 40px 40px;
    border: 1.5px solid #FFF;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
    backdrop-filter: blur(21px);

    .review {
        position: relative;

        &:before {
            content: '“';
            position: absolute;
            color: var(--White, #EAE0FF);
            font-family: Montserrat,serif;
            font-size: 40px;
            font-style: normal;
            font-weight: 900;
            line-height: 140%; /* 56px */

            left: -30px;
            top: -20px;
        }

        &:after {
            content: '”';
            position: absolute;
            font-family: Montserrat,serif;
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
    padding-bottom: 50px;

    .buttons {
        float: right;

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
