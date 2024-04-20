<script setup>
import AuthenticationCard from "@/Components/AuthenticationCard.vue";

defineProps({
    email: {
        type: String,
        default: ''
    }
})
</script>

<template>
    <AuthenticationCard>
        <template #logo> </template>
        <div class="mt-5 flex flex-col gap-y-12 text-center">
            <h1 class="text-violet-100 sm:text-2xl text-xl font-bold font-['Open Sans'] leading-10">Новый пароль выслан на почту <span class="font-light">{{email}}</span></h1>
            <h1 class="text-violet-100 sm:text-lg text-sm font-normal font-['Open Sans'] leading-10">Если письмо не пришло, проверьте папку “Спам”</h1>
        </div>
    </AuthenticationCard>
</template>

<style scoped lang="scss">

</style>
