<script setup>
import { NCheckbox } from "naive-ui";
import { checkboxThemeOverrides } from "@/themeOverrides.js";
import CallbackForm from "@/Components/Home/CallbackForm.vue";
</script>

<template>
    <div
        class="consultation flex flex-col items-center justify-center sm:mt-36 mt-28 pb-16"
    >
        <div
            class="text-center text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-violet-600 lg:text-6xl sm:text-5xl text-3xl font-bold font-['Open Sans']"
        >
            Требуется консультация <br />по работе сервиса?
        </div>
        <CallbackForm />
    </div>
</template>
