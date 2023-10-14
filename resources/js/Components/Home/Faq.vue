<script setup>

import FaqCard from "@/Components/Home/FaqCard.vue";
</script>

<template>
    <div class="pt-44">
        <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10 text-center">Вопрос — Ответ</h1>
        <div class="flex flex-col gap-4 pt-40">
            <faq-card v-for="i in 4"/>
        </div>
    </div>

</template>

<style scoped lang="scss">
h1{
    position: relative;
    &:after{
        content: '';
        position: absolute;
        left: 3%;
        bottom: 50%;
        transform: translateY(60%);
        width: 100%;
        height: 500%;
        background: url("/images/faq-plane.svg") no-repeat center;
    }
}
</style>
