<script setup>
import {computed, onMounted, onUnmounted, ref, watch, watchEffect} from "vue";
import {useKeenSlider} from "keen-slider/vue.es";
import "keen-slider/keen-slider.min.css";

const props = defineProps({
    interactive: Boolean,
    slidesPerView: {
        type: [Number, String],
        default: 1,
    },
    spaceBetween: {
        type: [Number, String],
        default: 0,
    },
    heightSetting: {
        type: [Boolean],
        default: false,
    },
});

const current = ref(1);

const [container, slider] = useKeenSlider({
    loop: true,
    slides: {
        origin: "auto",
        perView: props.slidesPerView,
        spacing: props.spaceBetween,
    },
    initial: current.value,
    slideChanged: (s) => {
        current.value = s.track.details.rel;
    },
});
const containerHeight = ref('');
const setContainerHeight = () => {
    if (props.heightSetting) {
        const containerElement = document.querySelector('.keen-slider');
        const height = containerElement.clientHeight + 50;
        containerHeight.value = `${height}px`;
    }
};

const dotHelper = ref([]);

const updateDotHelper = () => {
    if (slider && slider.value && slider.value.track && slider.value.track.details && slider.value.track.details.slides && slider.value.track.details.slides.length) {
        dotHelper.value = [...Array(slider.value.track.details.slides.length).keys()];
    } else {
        dotHelper.value = [];
    }
};

setTimeout(() => {
    slider.value.update();
    updateDotHelper(); // Update dotHelper after 3 seconds
}, 3000);

const onSlideChange = () => {
    current.value = slider.value.details().s.track.details.rel;
};

onMounted(() => {
    window.addEventListener("resize", slider.value.refresh);
    setContainerHeight();
    updateDotHelper()
});

onUnmounted(() => {
    window.removeEventListener("resize", slider.value.refresh);
});
</script>

<template>
    <div class="main w-full">
        <div v-show="interactive" class="interactive pb-4">
            <div class="buttons flex gap-3 items-center">
                <button
                    class="arrow arrow_left"
                    @click="slider.prev"
                    :class="{ 'arrow--disabled': current === 0 }"
                >
                    <img src="/images/arrow.svg" alt="arrow" />
                </button>
                <div
                    class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed"
                >
                    {{ current + 1 }} / {{ slider?.track?.details?.slides?.length }}
                </div>
                <button
                    class="arrow arrow_right"
                    @click="slider.next"
                    :class="{ 'arrow--disabled': current === slider?.track?.details?.slides?.length - 1 }"
                >
                    <img src="/images/arrow.svg" alt="arrow" />
                </button>
            </div>
        </div>
        <div class="w-full mb-2">
            <div ref="container" class="keen-slider" :style="{ height: heightSetting ? containerHeight : 'auto' }">
                <slot name="slider"/>
            </div>
        </div>
        <div class="dots my-14">
            <template v-if="dotHelper">
                <button
                    v-for="(_slide, idx) in dotHelper"
                    @click="slider.moveToIdx(idx)"
                    :class="{ dot: true, active: current === idx }"
                    :key="idx"
                ></button>
            </template>
        </div>

    </div>
</template>

<style scoped lang="scss">
.main {
    position: relative;
}

.arrow {
    width: 30px;
    height: 30px;
    cursor: pointer;

    img {
        width: 100%;
        height: auto;
    }
}

.arrow--disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.interactive {
    width: 100%;
    padding-bottom: 50px;

    .buttons {
        margin-left: auto;
        width: fit-content;

        .arrow {
            &:hover {
                filter: brightness(0.8);
            }
        }

        .arrow_right {
            transform: rotate(180deg);
        }
    }
}

.dots {
    display: flex;
    justify-content: center;
    align-items: center;
    .dot {
        width: 5px !important;
        height: 5px !important;
        border-radius: 30px !important;
        background: #8367bd !important;
        opacity: 1 !important;
        margin: 0 5px !important;
        @media screen and (max-width: 640px) {
            margin: 0 2px !important;
        }
    }

    .active {
        width: 26px !important;
        height: 8px !important;
        border-radius: 30px !important;
        background: #fff !important;
    }
}
@media screen and (min-width: 1025px) {
    .dot {
        width: 40px !important;
        height: 4px !important;
        border-radius: 65px !important;
        background: #fff !important;
        border: 1px solid #fff !important;
        opacity: 1 !important;
        margin: 0 2px !important;
    }
    .active {
        height: 4px !important;
        background: transparent !important;
    }
}
</style>
