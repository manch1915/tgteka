<script setup>
import CallbackForm from "@/Components/Home/CallbackForm.vue";
</script>

<template>
    <div
        class="consultation flex flex-col items-center justify-center sm:mt-36 mt-28 pb-16"
    >
        <div
            class="text-center sm:pb-14 pb-4 px-4 text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-violet-600 sm:text-5xl text-3xl font-bold font-['Open Sans']"
        >
            Остались вопросы?
        </div>
        <div
            class="text-center text-violet-100 px-4 text-base font-bold font-['Open Sans'] leading-tight"
        >
            Заполните форму и мы с вами свяжемся
        </div>
        <CallbackForm />
    </div>
</template>
