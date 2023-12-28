<script setup>
import CallbackForm from "@/Components/Home/CallbackForm.vue";
</script>

<template>
    <div class="issues flex flex-col items-center justify-center mt-40 sm:pb-20 pb-36 p-4 overflow-hidden">
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
    background: url("/images/issues.svg") no-repeat center;
    background-position-y: 0;
    background-size: cover;
    padding-top: 400px;
    @media screen and (max-width: 640px) {
        padding-top: 100px;
    }
}
</style>
