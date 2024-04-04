<script setup>
const props = defineProps({
    number: Number,
    header: String,
    paragraph: String,
    reversed: {
        default: false,
        type: Boolean,
    },
});
</script>

<template>
    <div
        :class="{ 'lg:justify-between': !props.reversed }"
        class="point-card lg:max-w-full max-w-lg lg:flex grid flex-col lg:items-center lg:text-center lg:justify-items-center gap-5"
    >
        <div
            v-if="props.reversed"
            class="point flex items-center justify-center"
        >
            <div
                class="point__number text-center text-violet-100 text-4xl font-bold font-['TT Norms'] uppercase leading-10"
            >
                {{ props.number }}
            </div>
        </div>
        <div class="lg:flex flex-col lg:items-center">
            <h1
                class="lg:w-80 point-header lg:text-center text-violet-100 text-xl font-bold font-['Open Sans'] leading-tight mb-3"
            >
                {{ props.header }}
            </h1>
            <p
                class="lg:w-64 point-paragraph lg:text-center text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
            >
                {{ props.paragraph }}
            </p>
        </div>

        <div
            v-if="!props.reversed"
            class="point flex items-center justify-center lg:mt-9"
        >
            <div
                class="point__number text-center text-violet-100 text-4xl font-bold font-['TT Norms'] uppercase leading-10"
            >
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
    background-color: #2e63d9;
    filter: drop-shadow(4px 1px 57px #2e63d9);
    z-index: 1;
}
.point-card {
    @media screen and (max-width: 1023px) {
        column-gap: 15px;
        row-gap: 16px;
        grid-template-columns: 60px calc(100% - 70px);
    }
    .point {
        @media screen and (max-width: 1023px) {
            grid-column: 1;
            grid-row: 1/3;
        }
    }
    .point-header {
        @media screen and (max-width: 1023px) {
            grid-column: 2;
        }
    }
    .point-paragraph {
        @media screen and (max-width: 1023px) {
            grid-column: 2;
        }
    }
}
</style>
