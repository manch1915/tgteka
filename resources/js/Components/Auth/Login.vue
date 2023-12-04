<script setup>
import {router, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import {useModalStore} from "@/stores/authModal.js";

const modalStore = useModalStore()

const switchTo = (modal) => {
    modalStore.setModalToOpen(modal)
}

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthenticationCard>
        <template #logo>
            Авторизация
        </template>

        <form @submit.prevent="submit">
            <div class="pt-10">
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="pt-2">
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>
            <div class="pt-4">
                <button @click.prevent="switchTo('recovery')" class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight cursor-pointer">Забыли пароль?</button>
            </div>
            <div class="pt-4 w-full">
                <button class="w-full text-center pr-6 py-3.5 bg-purple-600 text-white text-lg font-bold font-['Open Sans'] leading-normal rounded-3xl">
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
                <button @click.prevent="switchTo('register')" class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight cursor-pointer">
                    Зарегистрироваться
                </button>
            </div>
        </form>
    </AuthenticationCard>
</template>
