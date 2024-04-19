<script setup>
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import { reactive, ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { NCheckbox, NInput, useLoadingBar, useMessage } from "naive-ui";
import {
    checkboxThemeOverrides,
    inputThemeOverrides,
} from "@/themeOverrides.js";
import { closeModal } from "jenesius-vue-modal";
import { vMaska } from "maska";
import { openLogin } from "@/utilities/authModals.js";

const state = reactive({
    form: {
        username: "",
        email: "",
    },
    errors: {},
});

const checkbox = ref(true);

const loading = useLoadingBar();
const message = useMessage();

function validateForm() {
    let isValid = true;
    state.errors = {};

    if (!state.form.username) {
        isValid = false;
        state.errors.username = ['Поле имя пользователя обязательно.']
    }

    if (!state.form.email) {
        isValid = false;
        state.errors.email = ['Поле электронная почта обязательно.']
    }

    return isValid;
}

const submit = async () => {
    if (validateForm()) {
        loading.start();
        await axios
            .post(route("register"), state.form)
            .then((res) => {
                loading.finish();
                message.info("Мы отправили ваш пароль на ваш e-mail");
                closeModal();
            })
            .catch((error) => {
                loading.error();
                state.errors = error.response.data.errors || {};
            });
    }
};
</script>

<template>
    <AuthenticationCard>
        <template #logo> Регистрация </template>

        <form @submit.prevent="submit" class="flex flex-col gap-y-3.5">
            <div class="pt-10">
                <NInput
                    id="username"
                    v-model:value="state.form.username"
                    type="text"
                    placeholder="Имя пользователя"
                    :status="((state.errors.username && state.errors.username.length > 0)) ? 'error' : 'success'"
                    :theme-overrides="inputThemeOverrides"
                    class="py-1.5 my-1 sm:!w-full"
                />
                <InputError
                    :message="state.errors.username && state.errors.username[0]"
                />
            </div>
            <div>
                <NInput
                    id="email"
                    v-model:value="state.form.email"
                    type="text"
                    placeholder="Электронная почта"
                    :status="((state.errors.email && state.errors.email.length > 0)) ? 'error' : 'success'"
                    :theme-overrides="inputThemeOverrides"
                    class="py-1.5 my-1 sm:!w-full"
                />
                <InputError
                    :message="state.errors.email && state.errors.email[0]"
                />
            </div>
            <div
                class="text-violet-100 text-xs font-normal font-['Open Sans'] leading-none"
            >
                Укажите вашу электронную почту, на этот адрес будет выслан ваш
                пароль.
            </div>
            <div class="flex items-center gap-x-2">
                <n-checkbox
                    v-model:checked="checkbox"
                    :theme-overrides="checkboxThemeOverrides"
                />
                <label
                    class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                    >Я согласен получать Email-рассылку от Название
                    компании</label
                >
            </div>
            <div class="w-full">
                <button
                    class="w-full text-center pr-6 py-3.5 btn_gradient-purple transition hover:bg-purple-800 text-white text-lg font-bold font-['Open Sans'] leading-normal rounded-3xl"
                >
                    Зарегистрироваться
                </button>
            </div>
            <div class="px-2">
                <span
                    class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                >
                    Нажимая на кнопку “Зарегистрироваться”, Вы соглашаетесь
                    <Link :href="route('rules')">
                        <span class="underline">Правилами</span>
                    </Link>
                    и
                    <Link :href="route('terms-of-service')">
                        <span class="underline"
                            >Пользовательским соглашением Сервиса</span
                        >
                    </Link>
                </span>
            </div>
            <hr class="border border-violet-100 border-opacity-40" />
            <div class="w-full flex justify-center gap-3">
                <div
                    class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                >
                    Уже есть аккаунт?
                </div>
                <button
                    @click.prevent="openLogin"
                    class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight cursor-pointer"
                >
                    Войти
                </button>
            </div>
        </form>
    </AuthenticationCard>
</template>
<style scoped>
.icons {
    img {
    }
}
</style>
