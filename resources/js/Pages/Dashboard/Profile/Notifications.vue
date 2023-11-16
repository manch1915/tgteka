<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import {checkboxThemeOverrides, inputThemeOverrides} from "@/themeOverrides.js";
import {NCheckbox, NInput} from "naive-ui";

</script>

<template>
    <AppLayout>
        <ProfileLayout>
            <div class="text-center sm:text-left">
                <p class="text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10">Настройка уведомлений</p>
            </div>
            <div class="px-4 sm:px-0">
            <div class="notification_wrapper">
                <div class="notification">
                    <div class="flex items-center gap-x-2.5">
                        <h2 class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">В Telegram</h2>
                        <img src="/images/telegram-logo.svg" alt="telegram-logo">
                    </div>
                    <p class="pb-6 pt-2 text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Вы еще не подключили бот уведомлений. Получайте оперативные уведомления и управляйте заказами</p>
                    <div class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                        <p class="py-1">1) Напишите юзернейм личного Telegram-аккаунта</p>
                        <p class="py-1">2) Нажмите Подключить бот</p>
                        <p class="py-1">3) В Telegam-бот нажмите Start внизу или напишите команду /start</p>
                    </div>
                    <div class="py-6">
                        <div>
                            <n-input class="py-1.5 my-1 sm:!w-2/4" placeholder="@channel или https://t.me/dr_amina_pirmanova" :theme-overrides="inputThemeOverrides"/>
                        </div>
                        <button class="sm:!w-2/4 w-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal bg-purple-600 rounded-3xl py-2">Подключить бот</button>
                    </div>
                    <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Также прочитайте</p>
                    <div class="flex items-center gap-x-2.5">
                        <p class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">Инструмент уведомлений для <span class="font-bold">командной работы</span> в Telegram</p>
                        <img src="/images/arrow-down.svg" alt="arrow">
                    </div>
                    <div class="py-6">
                        <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">1) Создайте новую группу в Telegram</p>
                        <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">2) Добавьте в нее участников, которые должны получать сообщения от учетной записи на платформе</p>
                        <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">3) Добавьте бот уведомлений Telega.in в качестве участника вашей группы</p>
                    </div>
                    <div class="flex items-center gap-x-2.5">
                        <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Прочитать подробную инструкцию по настройке</p>
                        <img src="/images/arrow-down.svg" alt="arrow">
                    </div>
                </div>
            </div>
            <div class="notification_wrapper">
                <div class="notification">
                    <div class="flex items-center gap-x-2.5">
                        <h2 class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">E-mail</h2>
                        <img src="/images/gmail.svg" alt="telegram-logo">
                    </div>
                    <p class="pb-6 pt-2 text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">На почту e****@admin.com</p>
                    <div>
                        <div class="flex items-baseline py-1 gap-x-2.5">
                            <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                            <p class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">Промо рассылки</p>
                        </div>
                        <div class="flex items-baseline py-1 gap-x-2.5">
                            <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                            <p class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">Системные сообщения</p>
                        </div>
                        <div class="flex items-baseline py-1 gap-x-2.5">
                            <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                            <p class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">Отключить уведомления о проектах</p>
                        </div>
                    </div>
                    <p class="py-6 text-violet-100 text-xs font-normal font-['Open Sans'] leading-none">Если вы не получили тестовый Email, проверьте папку Спам, и нажмите на кнопку «Не спам»</p>
                    <h1 class="py-6 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Дополнительные Email адреса</h1>
                    <div class="flex flex-col gap-x-2.5 sm:w-3/4 sm:flex-row">
                        <n-input class="py-1.5 my-1 sm:!w-3/4" placeholder="Электронная почта" :theme-overrides="inputThemeOverrides"/>
                        <button class="bg-purple-600 rounded-full px-6 py-4 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Добавить</button>
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
    background: linear-gradient(to right bottom, #676A7D, #1C2D4B);
    border-radius: 0px 50px 50px 50px;
    .notification {
        padding: 40px;
        background: linear-gradient(to right bottom, #676A7D, #060B2A);
        border-radius: 0px 50px 50px 50px;
    }
}
</style>
