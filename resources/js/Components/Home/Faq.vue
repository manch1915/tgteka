<script setup>

import FaqCard from "@/Components/Home/FaqCard.vue";
</script>

<template>
    <div class="-mt-[450px]">
        <div class="strelka w-full overflow-hidden py-4 h-32">
            <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10 text-center">Вопрос — Ответ</h1>
        </div>
        <div class="flex flex-col gap-4 pt-40 px-3">
            <faq-card v-for="i in 4"/>
        </div>
    </div>

</template>

<style scoped lang="scss">
.strelka{
    position: relative;
    &:after{
        content: '';
        position: absolute;
        left: 3%;
        bottom: 50%;
        transform: translateY(60%);
        width: 100%;
        height: 100%;
        background: url("/images/faq-plane.svg") no-repeat center;
        background-size: 44vw;
    }
}
</style>
