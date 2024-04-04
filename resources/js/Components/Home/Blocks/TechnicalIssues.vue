<script setup>
import CallbackForm from "@/Components/Home/CallbackForm.vue";
</script>

<template>
    <div
        class="bg_fon issues flex flex-col items-center justify-center mt-40 sm:pb-20 pb-0 p-4"
    >
        <div
            class="text-center sm:pb-14 text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-violet-600 sm:text-5xl text-3xl font-bold font-['Open Sans']"
        >
            Возникли технические вопросы: <br />
            проблемы с доступом или оплатой?
        </div>
        <div
            class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-tight"
        >
            Заполните форму и мы с вами свяжемся
        </div>
        <CallbackForm />
    </div>
</template>

<style scoped lang="scss"></style>
