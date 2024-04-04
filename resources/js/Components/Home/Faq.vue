<script setup>
import FaqCard from "@/Components/Home/FaqCard.vue";
</script>

<template>
    <div class="container mx-auto mt-28">
        <div class="strelka w-full overflow-hidden py-4 h-32">
            <h1
                class="text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10 text-center"
            >
                Вопрос — Ответ
            </h1>
        </div>
        <div class="faq-radial flex flex-col gap-4 sm:mt-28 mt-10 mx-3">
            <faq-card v-for="i in 4" />
        </div>
    </div>
</template>

<style scoped lang="scss">
.faq-radial {
    position: relative;
    &:before {
        content: "";
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: radial-gradient(
            50% 50% at 50% 50%,
            rgba(125, 48, 255, 0.35) 0%,
            rgba(174, 126, 255, 0) 100%
        );
        width: 100vw;
        height: 150%;
    }
}
.strelka {
    position: relative;
    &:after {
        content: "";
        position: absolute;
        left: 3%;
        bottom: 40%;
        transform: translateY(60%);
        width: 100%;
        height: 100%;
        background: url("/images/faq-plane.svg") no-repeat center;
        background-size: 300px 500px;
    }
}
</style>
