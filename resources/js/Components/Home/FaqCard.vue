<script setup>
import {ref} from "vue";

const show = ref(false)
</script>

<template>
    <div class="faq p-10">
        <div class="flex justify-between items-center">
            <h1 class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Vestibulum ligula nulla, convallis ?</h1>
            <div class="arrow" @click="show = !show">
                <img :class="show ? 'rotate-90' : '-rotate-90'" src="/images/arrow.svg" alt="">
            </div>
        </div>
        <Transition>
            <p v-show="show" class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight pt-4">Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва</p>
        </Transition>
    </div>
</template>

<style scoped lang="scss">
.faq{
    border-radius: 1px 40px 40px 40px;
    border: 1.5px solid #FFF;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
    backdrop-filter: blur(21px);

}
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
.arrow{
    cursor: pointer;
    img{
        transition: all 0.5s;
        &:hover{
            filter: brightness(5);
        }
    }
}
</style>
