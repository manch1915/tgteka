<script setup>
import CallbackForm from "@/Components/Home/CallbackForm.vue";
</script>

<template>
    <div class="issues flex flex-col items-center justify-center mt-40 sm:pb-20 pb-0 p-4 overflow-hidden">
        <div class="text-center py-6 text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-violet-600 sm:text-6xl text-3xl font-bold font-['Open Sans'] ">
            Возникли технические вопросы: <br>
            проблемы с доступом или оплатой?
        </div>
        <div class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-tight">Заполните форму и мы с вами свяжемся</div>
        <CallbackForm/>
    </div>
</template>

<style scoped lang="scss">
.issues {
    position: relative;
    background: url("/images/issues.svg") no-repeat center;
    background-position-y: 0;
    background-size: cover;
    padding-top: 400px;
    @media screen and (max-width: 640px) {
        padding-top: 100px;
    }

    &::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 8%; /* adjust as per your need */
        background: linear-gradient(to bottom, transparent, #070B28); /* change 'white' to the color of your page background */
        @media screen and (max-width: 640px){
            height: 1%;
        }

    }
}
</style>
