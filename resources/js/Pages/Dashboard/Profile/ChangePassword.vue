<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import { inputThemeOverrides } from "@/themeOverrides.js";
import {NInput, useLoadingBar} from "naive-ui";
import {computed, reactive} from "vue";
import axios from "axios";
import {Head, usePage} from "@inertiajs/vue3";
import {openModal} from "jenesius-vue-modal";
import PasswordGeneration from "@/Components/Dashboard/PasswordGeneration.vue";

const form = reactive({
    password: "",
    password_confirmation: "",
});
const errors = reactive({
    password: [],
    password_confirmation: [],
});
const loading = useLoadingBar();

const page = usePage();


const userEmail = computed(() => page.props.auth.user.email);

const submit = async () => {
    loading.start();
    await axios
        .patch(route("change-password.update"), form)
        .then((res) => {
            loading.finish();
            errors.password = [];
            errors.password_confirmation = [];
        })
        .catch((error) => {
            if (
                error.response &&
                error.response.data &&
                error.response.data.errors
            ) {
                errors.password = error.response.data.errors.password;
                errors.password_confirmation =
                    error.response.data.errors.password_confirmation;
            }
            loading.error();
        });
};
const generatePassword = async () => {
    loading.start();
    await axios
        .post(route("change-password.generate"))
        .then(() => {
            loading.finish();
            openModal(PasswordGeneration, {email: userEmail.value})
        })
        .catch(() => {
            loading.error();
        });
};
</script>

<template>
    <Head>
        <title>Изменение пароля</title>
    </Head>

    <AppLayout>
        <ProfileLayout>
            <div class="text-center sm:text-left">
                <p
                    class="text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10"
                >
                    Изменение пароля
                </p>
            </div>
            <div class="mt-8 mb-6 text-center sm:text-left">
                <p
                    class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"
                >
                    Новый пароль
                </p>
            </div>
            <div class="flex flex-col gap-y-2 px-2 sm:w-3/4 sm:px-0">
                <div>
                    <n-input
                        class="py-1.5 my-1 sm:!w-2/4"
                        v-model:value="form.password"
                        placeholder="Новый пароль"
                        type="password"
                        show-password-on="click"
                        :theme-overrides="inputThemeOverrides"
                    />
                    <span class="block leading-4 text-errorred" v-if="errors.password">{{
                        errors.password[0]
                    }}</span>
                </div>
                <div>
                    <n-input
                        class="py-1.5 my-1 sm:!w-2/4"
                        v-model:value="form.password_confirmation"
                        placeholder="Подтвердите пароль"
                        type="password"
                        show-password-on="click"
                        :theme-overrides="inputThemeOverrides"
                    />
                    <span
                        class="block leading-4 text-errorred"
                        v-if="errors.password_confirmation"
                        >{{ errors.password_confirmation[0] }}</span
                    >
                </div>
                <button
                    @click.prevent="submit"
                    class="sm:w-2/4 transition-colors border border-violet-700 hover:bg-transparent text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal btn_gradient-purple rounded-full py-4"
                >
                    Обновить пароль
                </button>
            </div>
            <div class="mt-8 px-2 sm:px-0">
                <h2
                    class="my-2 text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"
                >
                    Также вы можете сгенерировать новый пароль
                </h2>
                <p
                    class="my-2 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                >
                    Пароль будет выслан на указанную вами почту<br />{{userEmail}}
                </p>
                <button
                    @click.prevent="generatePassword"
                    class="my-2 border py-2 px-4 border-violet-700 transition-colors hover:bg-violet-700 rounded-3xl text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                >
                    Сгенерировать
                </button>
            </div>
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss"></style>
