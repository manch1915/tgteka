<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import { computed, reactive, ref } from "vue";
import { vMaska } from "maska";
import { Head, Link, usePage } from "@inertiajs/vue3";
import axios from "axios";
import { NInput, useLoadingBar, useMessage } from "naive-ui";
import { inputThemeOverrides } from "@/themeOverrides.js";
import twoFactorAuthenticationModal from "@/Components/Auth/twoFactorAuthenticationModal.vue";
import { closeModal, pushModal } from "jenesius-vue-modal";
import TgLogin from "@/Components/Home/TgLogin.vue";

const props = defineProps({
    created_at: String,
    user: Object,
});

const page = usePage();
const message = useMessage();
const user = computed(() => props.user);

const form = reactive({
    username: ref(user.value.username),
    telegram_username: ref(user.value.telegram_username),
    mobile_number: ref(user.value.mobile_number),
});

const errors = reactive({
    username: ref([]),
    mobile_number: ref([]),
});
const loading = useLoadingBar();

const languages = [
    {
        label: "русский",
        value: "russian",
    },
    {
        label: "английский",
        value: "english",
    },
];

const isTwoFactorEnabled = computed(() => user.value.two_factor_enabled);

const twoFactorAuthentication = () => {
    pushModal(twoFactorAuthenticationModal);
};

const handleUserAuth = (user) => {
    console.log(user)
};

const submit = async () => {
    loading.start();
    await axios
        .patch(route("personal-data.store"), form)
        .then(() => {
            loading.finish();

            errors.username = [];
            errors.telegram_username = [];
            errors.mobile_number = [];
        })
        .catch((error) => {
            if (
                error.response &&
                error.response.data &&
                error.response.data.errors
            ) {
                errors.username = error.response.data.errors.username;
                errors.mobile_number = error.response.data.errors.mobile_number;
            }
            loading.error();
        });
};

const disableTwoFactorAuthentication = () => {
    axios
        .post(route("two-factor.disable"))
        .then(() => {
            message.success("Двухэтапная аутентификация успешно отключен");
            closeModal();
        })
        .catch((e) => {
            console.log(e);
        });
};
const deleteUser = async () => {
    const confirmation = window.confirm("Удалить пользователя?");
    if (confirmation) {
        loading.start();
        await axios
            .delete(route("personal-data.destroy"))
            .then(() => {
                loading.finish();
                window.location.href = "/";
            })
            .catch((error) => {
                loading.error();
                message.error(error?.response?.data?.message)
            });
    }
};
</script>

<template>
    <Head>
        <title>Личные данные</title>
    </Head>

    <AppLayout>
        <ProfileLayout>
            <div class="text-center lg:text-left">
                <p
                    class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10"
                >
                    Личные данные
                </p>
            </div>
            <div
                class="sm:px-0 px-4 pt-4 text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
            >
                дата регистрации:
                {{ created_at }}
            </div>
            <div class="mt-12 flex flex-col gap-y-6 px-4 sm:px-0">
                <p
                    class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                >
                    Имя пользователя
                </p>
                <div>
                    <n-input
                        v-model:value="form.username"
                        class="py-1.5 my-1 sm:!w-1/2"
                        placeholder="Имя пользователя"
                        :theme-overrides="inputThemeOverrides"
                    />
                    <span class="text-errorred leading-4 block" v-if="errors.username">{{
                        errors.username[0]
                    }}</span>
                </div>
                <p
                    class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                >
                    Telegram-аккаунт
                </p>
                <div>
                    <TgLogin/>
                </div>
                <p
                    class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                >
                    Телефон
                </p>
                <div>
                    <n-input
                        v-maska
                        :input-props="{ 'data-maska': '+7 (###) ###-##-##' }"
                        v-model:value="form.mobile_number"
                        class="py-1.5 my-1 sm:!w-1/2"
                        placeholder="+7 (___) ___-__-__"
                        :theme-overrides="inputThemeOverrides"
                    />
                    <span class="text-errorred leading-4 block" v-if="errors.mobile_number">{{
                        errors.mobile_number[0]
                    }}</span>
                </div>
            </div>
            <div class="px-4 sm:px-0">
                <button
                    @click.prevent="submit"
                    class="mt-6 px-6 py-4 bg-purple-600 transition hover:btn_gradient-purple rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                >
                    Сохранить данные
                </button>
                <br />
                <button
                    @click.prevent="twoFactorAuthentication"
                    :disabled="isTwoFactorEnabled"
                    class="mt-12  text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight underline"
                >
                    {{
                        isTwoFactorEnabled
                            ? "Двухэтапная аутентификация включена"
                            : "Включить двухэтапную аутентификацию"
                    }}
                </button>
                <button
                    @click.prevent="disableTwoFactorAuthentication"
                    class="mt-6 text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight underline"
                    v-if="isTwoFactorEnabled"
                >
                    Отключить двухэтапную аутентификацию
                </button>
                <Link
                    as="button"
                    :href="route('logout')"
                    method="post"
                    class="block mt-6 text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight underline"
                >
                    Выйти
                </Link>
                <button
                    v-if="$page.props.userRole !== 'Admin'"
                    @click.prevent="deleteUser"
                    class="mt-6 text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight underline"
                >
                    Удалить профиль
                </button>
            </div>
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss"></style>
