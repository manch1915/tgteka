<script setup>

import {ref} from "vue";

const windowWidth = ref(window.innerWidth)
</script>
<template>
    <div class="mission">
        <div class="container mx-auto">
            <div class="py-6 flex flex-col items-center justify-center">
                <p class="text-bold text-paleblue text-center py-6">Наша миссия</p>
                <div class="text-center sm:text-3xl sm:p-0 px-4 text-lg leading-snug font-bold font-['Open Sans'] sm:leading-10">
                    <span class="text-violet-100">
                        Объединить рекламодателей и владельцев Telegram-каналов на выгодных <br/>для обеих сторон условиях.
                    </span>
                    <br class="sm:none block">
                    <span class="text-slate-600">
                        Мы даём им простой инструмент для взаимовыгодного <br> <i class="hacker-icon -mb-2"></i>сотрудничества и открываем новые возможности для роста.
                    </span>
                </div>
<!--TODO slider for cards                -->
                <div :class="windowWidth <= 640 ? 'cards w-full' : 'cards flex gap-6 pt-24' ">
                    <slot name="cards">

                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">

i.hacker-icon{
    width: 111px;
    height: 40px;
    content:  url("/images/hacker.svg");
    @media screen and (max-width: 640px) {
        width: 79px;
        height: 28px;
    }
}

</style>
