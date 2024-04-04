<script setup>
import { ref } from "vue";

const show = ref(false);
</script>

<template>
    <div class="faq border__fon p-10">
        <div class="flex justify-between items-center">
            <h1
                class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"
            >
                Vestibulum ligula nulla, convallis ?
            </h1>
            <div class="arrow" @click="show = !show">
                <img
                    :class="show ? 'rotate-90' : '-rotate-90'"
                    src="/images/arrow.svg"
                    alt=""
                />
            </div>
        </div>
        <Transition>
            <p
                v-show="show"
                class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight pt-4"
            >
                Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва
                Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва
                Текст отзыва Текст отзыва Текст отзыва
            </p>
        </Transition>
    </div>
</template>

<style scoped lang="scss">
.faq {
    border-radius: 1px 40px 40px 40px;
    background: linear-gradient(0deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 100%);
    -webkit-backdrop-filter: blur(21px);
    backdrop-filter: blur(21px);
    &:after {
        padding: 1.5px;
    }

}
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
.arrow {
    cursor: pointer;
    img {
        transition: all 0.5s;
        &:hover {
            filter: brightness(5);
        }
    }
}
</style>
