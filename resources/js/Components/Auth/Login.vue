<script setup>
import { useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import InputError from '@/Components/InputError.vue';
import { openPasswordRecovery, openRegister } from "@/utilities/authModals.js";
import { NInput } from 'naive-ui'
import {inputThemeOverrides} from "@/themeOverrides.js";
import {ref} from "vue";

const form = useForm({
    username: '',
    password: '',
});

const errors = ref({username: null, password: null})

const validateForm = () => {
    errors.value.username = form.username.length < 1 ? 'Username is required' : null;
    errors.value.password = form.password.length < 1 ? 'Password is required' : null;
}

const submit = () => {
    validateForm();
    if (!(errors.value.username || errors.value.password)) {
        form.post(route('login.post'), {
            onFinish: () => form.reset('password'),
        });
    }
};
</script>

<template>
    <AuthenticationCard>
        <template #logo>
            Авторизация
        </template>

        <form @submit.prevent="submit">
            <div class="pt-10">
                <NInput
                    id="email"
                    v-model:value="form.username"
                    type="text"
                    placeholder="Имя пользователя"
                    :status="form.username ? 'success' : 'error'"
                    :theme-overrides="inputThemeOverrides"
                    class="py-1.5 my-1 sm:!w-full"
                />
                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <div class="pt-2">
                <NInput
                    id="password"
                    v-model:value="form.password"
                    type="password"
                    placeholder="Пароль"
                    :status="form.password ? 'success' : 'error'"
                    :theme-overrides="inputThemeOverrides"
                    class="py-1.5 my-1 sm:!w-full"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>
            <div class="pt-4">
                <button type="button" @click.prevent="openPasswordRecovery()" class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight cursor-pointer">Забыли пароль?</button>
            </div>
            <div class="pt-4 w-full">
                <button type="submit" class="w-full text-center pr-6 py-3.5 bg-purple-600 transition hover:bg-purple-800 text-white text-lg font-bold font-['Open Sans'] leading-normal rounded-3xl">
                   Войти
                </button>
            </div>
            <div class="pt-4 login-with">
                <p class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Войти через</p>
                <div class="icons flex gap-4 pt-4">
                    <a :href="route('telegram-redirect')"><img class="hover:cursor-pointer" src="/images/loginTelegram.svg" alt=""></a>
                    <img class="hover:" src="/images/loginVk.svg" alt="">
                </div>
            </div>
            <hr class="mt-4 border border-violet-100 border-opacity-40">
            <div class="pt-4 w-full flex justify-center gap-3">
                <div class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Нет аккаунта?</div>
                <button @click.prevent="openRegister()" class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight cursor-pointer">
                    Зарегистрироваться
                </button>
            </div>
        </form>
    </AuthenticationCard>
</template>
