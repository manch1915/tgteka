<script setup>
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import {inputThemeOverrides} from "@/themeOverrides.js";
import {NInput, useLoadingBar} from "naive-ui";
import {ref} from "vue";

const loading = useLoadingBar()

const email = ref('')
const recoverPassword = () => {
    loading.start()
    axios.post(route('password.forgot'), {email: email.value})
        .then(r => {
            loading.finish()
        })
        .catch(c => loading.error())
}
</script>

<template>
    <AuthenticationCard>
        <template #logo>
            Восстановление пароля
        </template>
        <div class="mt-5 flex flex-col">
            <n-input v-model:value="email" :theme-overrides="inputThemeOverrides" class="py-1" placeholder="Электронная почта"/>
            <button @click.prevent="recoverPassword" class="py-1 mt-2 text-white text-lg font-bold font-['Open Sans'] leading-normal bg-purple-600 rounded-3xl">Войти</button>
        </div>
    </AuthenticationCard>
</template>

<style scoped lang="scss">

</style>
