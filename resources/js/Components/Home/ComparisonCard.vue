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
            <div v-for="i in props.item.icons" :class="'icons_el icons_el-' + i.class" >
                <div class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">
                    {{i.text}}
                </div>
            </div>
        </div>
        <h3 class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">{{ props.item.header }}</h3>
        <ul class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-loose gap-4">
            <li v-for="(text, index) in props.item.listText" :key="index">{{ text }}</li>
        </ul>
        <div v-if="props.item.hasButton">
            <button class="px-6 py-2 bg-purple-600 transition hover:bg-purple-800 rounded-full text-violet-100 text-base font-bold leading-normal">
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
    position: relative;
    border-radius: 0 2rem  2rem 2rem;
    border: 1px solid #3b82f6;
    backdrop-filter: blur(10px);
    gap: 1.5rem;
    @media screen and (max-width: 640px) {
        max-width: 95%;
    }
    ul {
        li {
            list-style: circle;
        }
    }
}
.icons {
    .icons_el {
        padding: 8px 12px;
        border-radius: 100px;
    }
    .icons_el-white {
        div{
            color: #070C29 !important;
        }
        border: 1px solid rgba(255, 255, 255, 0.10);
        background: var(--White, #EAE0FF);
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
    }
    .icons_el-blue {
        border: 1px solid rgba(255, 255, 255, 0.10);
        background: #171961;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
    }
}
</style>
