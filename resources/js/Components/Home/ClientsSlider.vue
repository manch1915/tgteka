<script setup>
import {Swiper, SwiperSlide} from 'swiper/vue';
import {Navigation, Pagination} from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import {ref, watch} from "vue";
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


const goToSlide = (index) => {
    if (mySwiper.value) {
        mySwiper.value.swiper.slideTo(index);
    }
};

watch(activeIndex, (newVal) => {

    bullets = '<div class="pagination_el"></div>'.repeat(newVal);
}, { immediate: true });
const modules = [Navigation, Pagination]
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
            <swiper :navigation="{ prevEl: '.arrow_left', nextEl: '.arrow_right'}" :pagination= "{el: '.pagination', clickable: true, renderBullet: renderBullet, bulletClass: 'pagination_el', bulletActiveClass: 'pagination_el-active' }"  @slide-change="onSlideChange"  :modules="modules" loop slides-per-view="1">
                <swiper-slide class="!grid !grid-cols-2 !gap-4">
                    <ReviewCard v-for="i in 4"/>
                </swiper-slide>
                <swiper-slide class="!grid !grid-cols-2 !gap-4">
                    <ReviewCard v-for="i in 4"/>
                </swiper-slide>
                <swiper-slide class="!grid !grid-cols-2 !gap-4">
                    <ReviewCard v-for="i in 4"/>
                </swiper-slide>
            </swiper>
        </div>
        <div class="pagination flex items-center content-center justify-center gap-6 pt-4"></div>
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
