<script setup>


</script>
<template>
    <div class="mission">
        <div class="container mx-auto">
            <div class="py-6 flex flex-col items-center justify-center">
                <p class="text-bold text-paleblue text-center py-6">Наша миссия</p>
                <div class="text-center">
                    <span class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">
                        Объединить рекламодателей и владельцев Telegram-каналов на выгодных <br/>для обеих сторон условиях.

                    </span>

                    <span class="text-slate-600 text-3xl font-bold font-['Open Sans'] leading-10">
                        Мы даём им простой инструмент для взаимовыгодного <br> <i class="hacker-icon -mb-2"></i>сотрудничества и открываем новые возможности для роста.
                    </span>
                </div>
                <div class="cards flex gap-6 py-6">
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
}

</style>
