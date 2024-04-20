<script setup>
import { useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import InputError from "@/Components/InputError.vue";
import { openPasswordRecovery, openRegister } from "@/utilities/authModals.js";
import { NInput } from "naive-ui";
import { inputThemeOverrides } from "@/themeOverrides.js";
import {reactive, ref} from "vue";
import {LoginWidget} from "vue-tg";

const form = useForm({
    username: "",
    password: "",
});

const state = reactive({
    touched: {
        username: false,
        email: false,
    },
});

const errors = ref({ username: null, password: null });

function validateForm() {
    let isValid = true;
    if (!form.username) {
        isValid = false;
        form.errors.username = 'Поле имя пользователя обязательно.'
    }

    if (!form.password) {
        isValid = false;
        form.errors.password = 'Поле пароль обязательно.'
    }

    return isValid;
}
const submit = () => {
    if (validateForm()) {
        form.post(route("login.post"), {
            onFinish: () => form.reset("password"),
        });
    }
};
const openTelegramRedirect = () => {
    window.open(route('telegram-redirect'), '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=400,height=400');
};

function handleUserAuth(LoginWidgetUser) {
    console.log(LoginWidgetUser)
}
</script>

<template>
    <AuthenticationCard>
        <template #logo> Авторизация </template>

        <form @submit.prevent="submit">
            <div class="pt-10">
                <NInput
                    id="email"
                    v-model:value="form.username"
                    type="text"
                    placeholder="Имя пользователя"
                    :status="((form.errors.username && form.errors.username.length > 0)) ? 'error' : 'success'"
                    :theme-overrides="inputThemeOverrides"
                    class="py-1.5 my-1 sm:!w-full"
                />
                <InputError :message="form.errors.username" />
            </div>

            <div class="pt-2">
                <NInput
                    id="password"
                    v-model:value="form.password"
                    type="password"
                    placeholder="Пароль"
                    :status="((form.errors.password && form.errors.password.length > 0)) ? 'error' : 'success'"
                    :theme-overrides="inputThemeOverrides"
                    class="py-1.5 my-1 sm:!w-full"
                />
                <InputError :message="form.errors.password" />
            </div>
            <div class="pt-4">
                <button
                    type="button"
                    @click.prevent="openPasswordRecovery()"
                    class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight cursor-pointer"
                >
                    Забыли пароль?
                </button>
            </div>
            <div class="pt-4 w-full">
                <button
                    type="submit"
                    class="w-full text-center pr-6 py-3.5 btn_gradient-purple transition hover:bg-purple-800 text-white text-lg font-bold font-['Open Sans'] leading-normal rounded-3xl"
                >
                    Войти
                </button>
            </div>
            <div class="pt-4 login-with">
                <p
                    class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight"
                >
                    Войти через
                </p>
                <div class="icons flex gap-4 pt-4">
                    <LoginWidget
                        bot-username="tgtekaa_bot"
                        @auth="handleUserAuth"
                        size="small"
                    />
                    <a :href="route('vk-redirect')"
                        ><img class="hover:" src="/images/loginVk.svg" alt=""
                    /></a>
                </div>
            </div>
            <hr class="mt-4 border border-violet-100 border-opacity-40" />
            <div class="pt-4 w-full flex justify-center gap-3">
                <div
                    class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                >
                    Нет аккаунта?
                </div>
                <button
                    @click.prevent="openRegister()"
                    class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight cursor-pointer"
                >
                    Зарегистрироваться
                </button>
            </div>
        </form>
    </AuthenticationCard>
</template>
