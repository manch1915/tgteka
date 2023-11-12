<script setup>
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import InputError from '@/Components/InputError.vue';
import { useModalStore } from '@/stores/authModal.js'
import TextInput from '@/Components/TextInput.vue';
import {reactive} from "vue";
import {Link} from "@inertiajs/vue3";
import {NCheckbox, useLoadingBar} from "naive-ui";
import {checkboxThemeOverrides} from "@/themeOverrides.js";

const modalStore = useModalStore()

const switchToLogin = () => {
    modalStore.setModalToOpen('login')
}

const state = reactive({
  form: {
    email: '',
    mobile_number: ''
  },
  errors: {}
});
const loading = useLoadingBar()
const submit = async () => {
    loading.start()
    await axios.post(route('register'), state.form)
        .then(res => {
            loading.finish()
        })
        .catch(error => {
            loading.error()
            state.errors = error.response.data.errors || {}
        })
};
</script>

<template>
    <AuthenticationCard>
        <template #logo>
            Регистрация
        </template>

        <form @submit.prevent="submit" class="flex flex-col gap-y-3.5">
            <div class="pt-10">
                <TextInput
                    id="email"
                    v-model="state.form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Электронная почта"
                />
              <InputError class="mt-2" :message="state.errors.email && state.errors.email[0]" />
            </div>
            <div class="text-violet-100 text-xs font-normal font-['Open Sans'] leading-none">Укажите вашу электронную почту, на этот адрес будет выслан ваш пароль.</div>
            <div>
                <TextInput
                    id="mobile_number"
                    v-model="state.form.mobile_number"
                    class="mt-1 block w-full"
                    required
                    type="tel"
                    autocomplete="current-mobile_number"
                    placeholder="+7 (___) ___-__-__"
                />
                <InputError class="mt-2" :message="state.errors.mobile_number && state.errors.mobile_number[0]" />
            </div>
            <div class="flex items-center gap-x-2">
                <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                <label for="rassilka" class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Я согласен получать Email-рассылку от Название компании</label>
            </div>
            <div class="w-full">
                <button class="w-full text-center pr-6 py-3.5 bg-purple-600 transition hover:bg-purple-800 text-white text-lg font-bold font-['Open Sans'] leading-normal rounded-3xl">
                    Зарегистрироваться
                </button>
            </div>
            <div class="px-2">
                  <span class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">
                    Нажимая на кнопку “Зарегистрироваться”, Вы соглашаетесь
                      <Link :href="route('rules')">
                    <span class="underline">Правилами</span>
                      </Link>
                    и
                      <Link :href="route('terms-of-service')">
                        <span class="underline">Пользовательским соглашением Сервиса</span>
                      </Link>
                  </span>
            </div>
            <hr class="border border-violet-100 border-opacity-40">
            <div class="w-full flex justify-center gap-3">
                <div class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Уже есть аккаунт? </div>
                <button @click.prevent="switchToLogin" class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight cursor-pointer">
                    Войти
                </button>
            </div>
        </form>
    </AuthenticationCard>
</template>
<style scoped>
.icons{
    img{

    }
}
</style>
