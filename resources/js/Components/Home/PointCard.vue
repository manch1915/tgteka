<script setup>

const props = defineProps({
    number: Number,
    header: String,
    paragraph: String,
    reversed: {
        default: false,
        type: Boolean
    }
})
</script>

<template>
    <div class="flex flex-col items-center justify-center text-center justify-items-center gap-3">
        <div v-if="!props.reversed" class="point flex items-center justify-center">
            <div class="point__number text-center text-violet-100 text-4xl font-bold font-['TT Norms'] uppercase leading-10">
                {{ props.number }}
            </div>
        </div>
        <h1 class="w-80 text-center text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">{{ props.header }}</h1>
        <p class="w-64 text-center text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">{{ props.paragraph }}</p>
        <div v-if="props.reversed" class="point flex items-center justify-center mt-12">
            <div class="point__number text-center text-violet-100 text-4xl font-bold font-['TT Norms'] uppercase leading-10">
                {{ props.number }}
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.point {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color:  #2E63D9;
    filter: drop-shadow(4px 1px 57px #2E63D9);
}
</style>
