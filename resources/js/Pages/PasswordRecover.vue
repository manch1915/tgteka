<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {closeModal} from "jenesius-vue-modal";
import { reactive } from "vue";
import {inputThemeOverrides} from "@/themeOverrides.js";
import {NInput} from "naive-ui";
import {router} from "@inertiajs/vue3";
closeModal()
const props = defineProps({
    token: String,
    ziggy: Object
})
const form = reactive({
    password: '',
    password_confirmation: '',
    token: props.token,
    email: props.ziggy.query.email,
    errors: []
})
const passwordReset = () => {
    axios.post(route('password.update'), form)
        .then(r => router.visit(route('owners')))
        .catch(e => {
            form.errors = e.response.data.errors;
        });
}
</script>
<template>
    <MainLayout>
        <div class="container mx-auto flex flex-col sm:w-1/3 gap-y-10 sm:p-0 p-2">
            <div class="text-center mt-5 text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Сброс пароля</div>
            <div>
                <n-input v-model:value="form.password" type="password" class="py-1.5 my-1 sm:!w-full" placeholder="Пароль" :theme-overrides="inputThemeOverrides"/>
                <span v-if="form.errors.password && form.errors.password.length" class="block my-1 text-red-500">{{form.errors.password[0]}}</span>
            </div>
            <div>
                <n-input v-model:value="form.password_confirmation" type="password" class="py-1.5 my-1 sm:!w-full" placeholder="Повторите пароль" :theme-overrides="inputThemeOverrides"/>
                <span v-if="form.errors.password && form.errors.password.length" class="block my-1 text-red-500">{{form.errors.password[1]}}</span>
            </div>
            <button @click.prevent="passwordReset" class="w-full px-6 py-2 bg-purple-600 transition hover:bg-purple-800 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Сбросить</button>
        </div>
    </MainLayout>
</template>
<style scoped lang="scss">
</style>
