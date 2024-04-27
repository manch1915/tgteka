<script setup>
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import { inputThemeOverrides } from "@/themeOverrides.js";
import { NInput, useLoadingBar,useMessage } from "naive-ui";
import { ref } from "vue";
import { closeModal } from "jenesius-vue-modal";
import {openLogin} from "@/utilities/authModals.js";

const loading = useLoadingBar();
const message = useMessage();
const email = ref("");
const recoverPassword = () => {
    loading.start();
    axios
        .post(route("password.forgot"), { email: email.value })
        .then((r) => {
            loading.finish();
            message.success('Сообщение с инструкциями по восстановлению пароля отправлено на вашу электронную почту.')
            closeModal();
        })
        .catch((c) => {
            loading.error()
            message.error('Произошла ошибка. Пожалуйста, попробуйте еще раз позже.')
        });
};
</script>

<template>
    <AuthenticationCard>
        <template #logo> Восстановление пароля </template>
        <div class="mt-5 flex flex-col">
            <n-input
                v-model:value="email"
                @keydown.enter.prevent="recoverPassword"
                :theme-overrides="inputThemeOverrides"
                class="py-1"
                placeholder="Электронная почта"
            />
            <button
                @click.prevent="recoverPassword"
                class="py-1 mt-2 text-white text-lg font-bold font-['Open Sans'] leading-normal btn_gradient-purple transition hover:bg-purple-800 rounded-3xl"
            >
                Восстановить
            </button>
            <div class="pt-4">
                <button
                    type="button"
                    @click.prevent="openLogin()"
                    class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight cursor-pointer transition hover:underline"
                >
                    Назад
                </button>
            </div>
        </div>
    </AuthenticationCard>
</template>

<style scoped lang="scss"></style>
