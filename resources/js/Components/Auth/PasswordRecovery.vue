<script setup>
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import {inputThemeOverrides} from "@/themeOverrides.js";
import {NInput, useLoadingBar} from "naive-ui";
import {ref} from "vue";
import {closeModal} from "jenesius-vue-modal";

const loading = useLoadingBar()

const email = ref('')
const recoverPassword = () => {
    loading.start()
    axios.post(route('password.forgot'), {email: email.value})
        .then(r => {
            loading.finish()
        })
        .catch(c => loading.error())
    closeModal()
}
</script>

<template>
    <AuthenticationCard>
        <template #logo>
            Восстановление пароля
        </template>
        <div class="mt-5 flex flex-col">
            <n-input v-model:value="email" @keydown.enter.prevent="recoverPassword" :theme-overrides="inputThemeOverrides" class="py-1" placeholder="Электронная почта"/>
            <button @click.prevent="recoverPassword" class="py-1 mt-2 text-white text-lg font-bold font-['Open Sans'] leading-normal bg-purple-600 transition hover:bg-purple-800 rounded-3xl">Восстановить</button>
        </div>
    </AuthenticationCard>
</template>

<style scoped lang="scss">

</style>
