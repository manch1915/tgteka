<script setup>
const props = defineProps({
    title: String,
    created_at: String
})
</script>

<template>
    <div class="card-border">
        <div class="card flex sm:flex-row flex-col-reverse justify-between px-8 py-6 cursor-pointer">
            <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">{{ title }}</p>
            <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">{{ created_at }}</p>
        </div>
    </div>
</template>

<style scoped lang="scss">
.card-border {
    padding: 2px;
    background: linear-gradient(to bottom, #70669E, #232056);
    border-radius: 0 30px 30px 30px;
}

.card {
    border-radius: 0 30px 30px 30px;
    background: linear-gradient(to top, #70669E, #232056);
    backdrop-filter: blur(21px);
}
</style>
