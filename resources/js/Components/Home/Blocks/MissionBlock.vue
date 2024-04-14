<script setup>
import { onMounted, onUnmounted, ref } from "vue";

const windowWidth = ref(window.innerWidth);

const updateWidth = () => {
    windowWidth.value = window.innerWidth;
};

onMounted(() => {
    // Add the updateWidth function as a window resize listener
    window.addEventListener("resize", updateWidth);
});

onUnmounted(() => {
    // Remove listener when component is unmounted
    window.removeEventListener("resize", updateWidth);
});
</script>
<template>
    <div class="mission">
        <div class="container mx-auto px-4">
            <div
                class="sm:pt-32 pt-14 flex flex-col items-center justify-center"
            >
                <p class="font-bold text-lg text-paleblue text-center pb-14">
                    Наша миссия
                </p>
                <div
                    class="text-center mission__text sm:text-3xl text-lg leading-snug font-bold font-['Open Sans'] sm:leading-10"
                >
                    <span class="text-violet-100">
                        Объединить рекламодателей и&nbsp;владельцев
                        Telegram-каналов на выгодных для&nbsp;обеих сторон
                        условиях.
                    </span>
                    <br class="sm:hidden" />
                    <span class="text-slate">
                        Мы даём им простой инструмент для&nbsp;взаимовыгодного
                        <i class="inline-block hacker-icon -mb-2 me-4"></i
                        >сотрудничества и&nbsp;открываем новые возможности для
                        роста.
                    </span>
                </div>

                <div
                    :class="
                        windowWidth <= 1024
                            ? 'cards w-full'
                            : 'cards flex gap-24 sm:pt-40 pt-32 flex-wrap justify-center'
                    "
                >
                    <slot name="cards"> </slot>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.mission__text {
    max-width: 1360px;
}
i.hacker-icon {
    pointer-events: none;
    width: 111px;
    height: 40px;
    content: url("/images/hacker.svg");
    @media screen and (max-width: 640px) {
        width: 79px;
        height: 28px;
    }
}
.text-slate {
    color: #575380;
}
</style>
