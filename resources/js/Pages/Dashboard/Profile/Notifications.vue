<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import {
    checkboxThemeOverrides,
    inputThemeOverrides,
} from "@/themeOverrides.js";
import { NCheckbox, NInput } from "naive-ui";
import { Head, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import TgLogin from "@/Components/Home/TgLogin.vue";

const page = usePage();

const telegram_username = computed(
    () => page.props.auth.user.telegram_username
);
</script>

<template>
    <Head>
        <title>Настройка уведомлений</title>
    </Head>

    <AppLayout>
        <ProfileLayout>
            <div class="text-center sm:text-left">
                <p
                    class="text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10"
                >
                    Настройка уведомлений
                </p>
            </div>
            <div class="px-4 sm:px-0">
                <div class="notification_wrapper">
                    <div class="notification">
                        <div class="flex items-center gap-x-2.5">
                            <h2
                                class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                            >
                                В Telegram
                            </h2>
                            <img
                                src="/images/telegram-logo.svg"
                                alt="telegram-logo"
                            />
                        </div>
                        <p
                            v-if="!telegram_username"
                            class="pb-6 pt-2 text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                        >
                            Вы еще не подключили бот уведомлений. Получайте
                            оперативные уведомления и управляйте заказами
                        </p>
                        <div class="py-6">
                            <TgLogin/>
                        </div>
                        <p
                            class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                        >
                            Также прочитайте
                        </p>
                        <div class="flex items-center gap-x-2.5">
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal"
                            >
                                Инструмент уведомлений для
                                <span class="font-bold">командной работы</span>
                                в Telegram
                            </p>
                            <img src="/images/arrow-down.svg" alt="arrow" />
                        </div>
                        <div class="py-6">
                            <p
                                class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                            >
                                1) Создайте новую группу в Telegram
                            </p>
                            <p
                                class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                            >
                                2) Добавьте в нее участников, которые должны
                                получать сообщения от учетной записи на
                                платформе
                            </p>
                            <p
                                class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                            >
                                3) Добавьте бот уведомлений 1-24.xyz в качестве
                                участника вашей группы
                            </p>
                        </div>
                        <div class="flex items-center gap-x-2.5">
                            <p
                                class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                            >
                                Прочитать подробную инструкцию по настройке
                            </p>
                            <img src="/images/arrow-down.svg" alt="arrow" />
                        </div>
                    </div>
                </div>
                <div class="notification_wrapper">
                    <div class="notification">
                        <div class="flex items-center gap-x-2.5">
                            <h2
                                class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                            >
                                E-mail
                            </h2>
                            <img src="/images/gmail.svg" alt="telegram-logo" />
                        </div>
                        <p
                            class="pb-6 pt-2 text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                        >
                            На почту e****@admin.com
                        </p>
                        <div>
                            <div class="flex items-baseline py-1 gap-x-2.5">
                                <n-checkbox
                                    :theme-overrides="checkboxThemeOverrides"
                                />
                                <p
                                    class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal"
                                >
                                    Промо рассылки
                                </p>
                            </div>
                            <div class="flex items-baseline py-1 gap-x-2.5">
                                <n-checkbox
                                    :theme-overrides="checkboxThemeOverrides"
                                />
                                <p
                                    class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal"
                                >
                                    Системные сообщения
                                </p>
                            </div>
                            <div class="flex items-baseline py-1 gap-x-2.5">
                                <n-checkbox
                                    :theme-overrides="checkboxThemeOverrides"
                                />
                                <p
                                    class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal"
                                >
                                    Отключить уведомления о проектах
                                </p>
                            </div>
                        </div>
                        <p
                            class="py-6 text-violet-100 text-xs font-normal font-['Open Sans'] leading-none"
                        >
                            Если вы не получили тестовый Email, проверьте папку
                            Спам, и нажмите на кнопку «Не спам»
                        </p>
                        <h1
                            class="py-6 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                        >
                            Дополнительные Email адреса
                        </h1>
                        <div
                            class="flex flex-col gap-x-2.5 sm:w-3/4 sm:flex-row"
                        >
                            <n-input
                                class="py-1.5 my-1 sm:!w-3/4"
                                placeholder="Электронная почта"
                                :theme-overrides="inputThemeOverrides"
                            />
                            <button
                                class="btn_gradient-purple rounded-full px-6 py-4 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                            >
                                Добавить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss">
.notification_wrapper {
    margin: 30px 0;
    padding: 3px;
    background: linear-gradient(to right bottom, #676a7d, #1c2d4b);
    border-radius: 0px 50px 50px 50px;
    .notification {
        padding: 40px;
        background: linear-gradient(to right bottom, #676a7d, #060b2a);
        border-radius: 0px 50px 50px 50px;
    }
}
</style>
