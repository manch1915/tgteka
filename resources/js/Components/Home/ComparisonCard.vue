<script setup>
const props = defineProps({
    item: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <div class="samo">
        <div class="icons flex gap-4">
<!--            TODO icons style-->
            <div class="px-6 py-4 text-violet-100 text-base font-bold font-['Open Sans'] leading-tight bg-blue-950 rounded-full shadow-inner border border-white border-opacity-10 justify-center items-center gap-2.5 inline-flex">
                ручной процесс
            </div>
            <div class="icons_el">
                <div class="text-violet-100 text-base font-bold font-['Open Sans'] leading-tight">
                    нет гарантий
                </div>
            </div>
        </div>
        <h3 class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">{{ props.item.header }}</h3>
        <ul class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-loose gap-4">
            <li v-for="(text, index) in props.item.listText" :key="index">{{ text }}</li>
        </ul>
        <div v-if="props.item.hasButton">
            <button class="px-6 py-2 bg-purple-600 rounded-full text-violet-100 text-base font-bold leading-normal">
                Создать проект
            </button>
        </div>
        <div v-if="props.item.showSaveTimeMoney" class="flex justify-end">
            <div>
            <span class="text-violet-100 text-2xl font-bold leading-loose">x</span>
            <span class="text-violet-100 text-4xl font-bold leading-10">2</span>
            <span class="text-violet-100 text-2xl font-bold leading-loose"> сэкономить <br/>время и деньги</span>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.samo {
    max-width: 70%;
    min-height: 100%;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
    display: flex;
    flex-direction: column;
    padding: 3rem 3rem 9rem;
    position: relative; /* origin-top-left */
    border-radius: 2.1875rem 0 2.1875rem 2.1875rem; /* rounded-tr-3xl rounded-bl-3xl rounded-br-3xl */
    border: 1px solid #3b82f6; /* border border-blue-950 */
    backdrop-filter: blur(10px); /* backdrop-blur-sm */
    gap: 1.5rem; /* gap-6 */
    ul {
        li {
            list-style: circle;
        }
    }
}
</style>
