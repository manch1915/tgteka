<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, FreeMode, Navigation, Pagination } from "swiper/modules";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/autoplay";
import { computed, ref } from "vue";

const props = defineProps({
    interactive: Boolean,
    slides: Number | String,
    freemode: {
        default: false,
    },
    slidesPerView: {
        type: [Number, String],
        default: 1,
    },
    spaceBetween: {
        type: [Number, String],
        default: 0,
    },
});

const spaceBetween = computed(() => {
    if (parseFloat(props.spaceBetween) > 0) {
        return parseFloat(props.spaceBetween);
    }
    return parseFloat(props.slidesPerView) > 1 ? 60 : 0;
});

let activeIndex = ref(1);
const onSlideChange = (swiper) => {
    activeIndex.value = swiper.realIndex + 1;
};
let mySwiper = ref();

// Swiper slide data

let bullets = ref("");

const pagination = ref({
    clickable: true,
    renderBullet: function (index, className) {
        return '<span class="' + className + '"></span>';
    },
});
const goToSlide = (index) => {
    if (mySwiper.value) {
        mySwiper.value.swiper.slideTo(index);
    }
};

const modules = [Navigation, Pagination, Autoplay, FreeMode];
</script>

<template>
    <div class="main w-full">
        <div v-show="interactive" class="interactive pb-4">
            <div class="buttons flex gap-3 items-center">
                <div class="arrow arrow_left">
                    <img src="/images/arrow.svg" alt="arrow" />
                </div>
                <div
                    class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed"
                >
                    {{ activeIndex }} / {{ slides }}
                </div>
                <div class="arrow arrow_right">
                    <img src="/images/arrow.svg" alt="arrow" />
                </div>
            </div>
        </div>
        <div class="w-full mb-10">
            <swiper
                :autoplay="{ delay: 2000 }"
                :free-mode="freemode"
                :navigation="{ prevEl: '.arrow_left', nextEl: '.arrow_right' }"
                :pagination="pagination"
                @slide-change="onSlideChange"
                :modules="modules"
                loop
                :slides-per-view="slidesPerView"
                :space-between="spaceBetween"
            >
                <slot name="slider" />
            </swiper>
        </div>
        <!-- <div
            class="pagination flex items-center content-center justify-center gap-6 pt-4"
            v-html="bullets"
        ></div> -->
    </div>
</template>

<style scoped lang="scss">
.block {
    padding: 50px 80px;
    border-radius: 1px 40px 40px 40px;
    border: 1.5px solid #fff;
    background: radial-gradient(
        278.82% 137.51% at 1.95% 3.59%,
        rgba(255, 255, 255, 0.26) 0%,
        rgba(255, 255, 255, 0) 100%
    );
    backdrop-filter: blur(21px);

    .review {
        position: relative;

        &:before {
            pointer-events: none;
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
    padding-bottom: 50px;

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
