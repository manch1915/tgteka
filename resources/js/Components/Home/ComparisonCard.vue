<script setup>
const props = defineProps({
    item: {
        type: Object,
        required: true
    },
    background: {
        type: Number,
        default: 1,
    }
})
</script>

<template>
    <div class="samo">
        <div class="flex gap-4 sm:pt-6 sm:px-12 p-2 icons">
            <div v-for="i in props.item.icons" :class="'icons_el icons_el-' + i.class" >
                <div class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">
                    {{i.text}}
                </div>
            </div>
        </div>
        <h3 class="text-violet-100 sm:pt-6 sm:px-12 p-2 sm:text-3xl text-xl font-bold font-['Open Sans'] sm:leading-10 leading-normal">{{ props.item.header }}</h3>
        <ul :class="{'pb-36': !props.item.hasButton}"  class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-[1.7rem] gap-4 pt-6 sm:px-12 px-10">
            <li v-for="(text, index) in props.item.listText" :key="index" :style="{ 'margin-bottom': index < props.item.listText.length - 1 ? '10px' : '0' }">{{ text }}</li>
        </ul>
        <div class="pt-6 pr-12 pl-12" v-if="props.item.hasButton">
            <button class="rounded-full bg-purple-600 px-6 py-4 text-base font-bold leading-normal text-violet-100 transition hover:bg-purple-800">
                Создать проект
            </button>
        </div>
        <div v-if="props.item.showSaveTimeMoney" class="flex justify-end pt-4 px-2 pb-36">
            <div>
            <span class="text-xl font-bold text-violet-100">x</span>
            <span class="text-2xl font-bold text-violet-100">2</span>
            <span class="text-xl font-bold text-violet-100"> сэкономить <br/>время и деньги</span>
            </div>
        </div>
        <template v-if="background === 0">
            <img class="card_line" src="/images/cardlines.png" alt="">
        </template>
       <template v-else>
           <img class="card_line" src="/images/cardlines2.png" alt="">
       </template>
    </div>
</template>

<style scoped lang="scss">
.samo {
    max-width: 70%;
    min-height: 100%;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.01) 0%, rgba(255, 255, 255, 0.00) 100%);
    display: flex;
    flex-direction: column;
    position: relative;
    border-radius: 0 2rem  2rem 2rem;
    border: 1px solid #3b82f6;
    backdrop-filter: blur(10px);

    @media screen and (max-width: 640px) {
        max-width: 95%;
    }
    ul {
        li {
            list-style: circle;
        }
    }
}
.card_line{
    position: absolute;
    bottom: 0;
    object-fit: cover;
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
