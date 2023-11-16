<script setup>
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import {NCheckbox, NInput, NTabPane, NTabs} from "naive-ui";
import {checkboxThemeOverrides, inputThemeOverrides, nTabSegmentsThemeOverrides} from "@/themeOverrides.js";
import {ref} from "vue";
const activeButton = ref('add-payment-method');

const activeTab = ref('bank-card')
</script>

<template>
    <AppLayout>
        <ProfileLayout>
            <div class="text-center sm:text-left">
                <p class="text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10">Пополнение средств</p>
            </div>
            <div class="mt-8 segment sm:w-3/4">
                <n-tabs :animated="true" :theme-overrides="nTabSegmentsThemeOverrides" type="segment" style="display: flex">
                    <n-tab-pane name="oasis">
                        <template #tab>
                            <button @click.prevent="activeButton = 'add-payment-method'" :class="['tab-button', 'transition', 'text-violet-100', 'text-lg', 'font-bold', 'font-[\'Open Sans\']', 'leading-normal', activeButton === 'add-payment-method' ? 'active' : '']">Физическое лицо</button>
                        </template>
                        <div>
                            <div class="payment_types">
                                <div class="grid grid-cols-2 gap-4 payment_types-cards sm:grid-cols-3">
                                    <div @click.prevent="activeTab = 'bank-card'" class="payment_types-card-wrapper">
                                        <div class="flex h-full flex-col items-center justify-center payment_types-card">
                                            <img src="/images/card-white.svg" alt="card-white">
                                            <p class="text-violet-100 text-lg font-normal font-['Open Sans']">Банковская карта</p>
                                        </div>
                                    </div>
                                    <div @click.prevent="activeTab = 'wallets'" class="payment_types-card-wrapper">
                                        <div class="flex h-full flex-col items-center justify-evenly payment_types-card">
                                            <div class="flex">
                                                <img class="w-16 sm:w-auto" src="/images/webmoney.svg" alt="webmoney">
                                                <img class="w-16 sm:w-auto" src="/images/qiwi.svg" alt="qiwi">
                                            </div>
                                            <p class="text-violet-100 text-lg font-normal font-['Open Sans']">Кошельки</p>
                                        </div>
                                    </div>
                                    <div @click.prevent="activeTab = 'qr'" class="payment_types-card-wrapper">
                                        <div class="flex h-full flex-col items-center justify-center payment_types-card">
                                            <img src="/images/qr.svg" alt="qr">
                                            <p class="text-violet-100 text-lg font-normal font-['Open Sans']">QR код</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="cursor-pointer sm:text-left text-center text-violet-100 text-lg font-normal font-['Open Sans'] underline">Пополнить другим способом</p>
                            <div v-show="activeTab === 'bank-card'" class="mt-5 px-4 bank-card sm:w-3/4 sm:px-0">
                                <h1 class="text-violet-100 sm:text-3xl sm:text-left text-center text-xl font-bold font-['Open Sans'] leading-10">Пополнение банковской картой</h1>
                                <div class="my-4 flex justify-center gap-x-5 sm:justify-start">
                                    <img src="/images/visa-logo.svg" alt="visa">
                                    <img src="/images/master-cart-logo.svg" alt="master-card">
                                    <img src="/images/mir-logo.svg" alt="mir">
                                </div>
                                <n-input class="py-1.5 my-1 sm:!w-3/4" placeholder="Сумма, рублей" :theme-overrides="inputThemeOverrides"/>
                                <div class="py-2">
                                    <p class="text-violet-100 text-base font-normal font-['Open Sans']">Комиссия системы 15% (не менее 85.0 р.)<br/>Максимальная сумма пополнения 170 000 р. <br/>При необходимости пополниться на сумму больше, совершите несколько платежей.</p>
                                </div>
                                <div class="flex items-center py-2 gap-x-2.5 sm:items-baseline">
                                    <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                                    <p class="text-violet-100 text-xs font-normal font-['Open Sans'] leading-normal">Привязывая карту, вы соглашаетесь <br/>с <span class="underline">правилами настройки автопополнения</span></p>
                                </div>
                                <div class="flex items-center py-2 gap-x-2.5 sm:items-baseline">
                                    <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                                    <p class="text-violet-100 text-xs font-normal font-['Open Sans'] leading-normal">Привязывая карту, вы соглашаетесь <br/>с <span class="underline">правилами настройки автопополнения</span></p>
                                </div>
                                <div class="flex items-center py-2 gap-x-2.5 sm:items-baseline">
                                    <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                                    <p class="text-violet-100 sm:text-lg text-xs font-normal font-['Open Sans'] leading-normal">Привязывая карту, вы соглашаетесь <br/>с <span class="underline">правилами настройки автопополнения</span></p>
                                </div>
                                <button class="sm:w-3/4 w-full my-2 bg-purple-600 rounded-3xl py-2 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Пополнить</button>
                            </div>
                            <div v-show="activeTab === 'wallets'" class="mt-5 px-4 wallets sm:w-3/4 sm:px-0">
                                <h1 class="text-violet-100 sm:text-3xl sm:text-left text-center text-xl font-bold font-['Open Sans'] leading-10">Пополнение кошельками</h1>
                                <div class="my-4 flex justify-center gap-x-5 sm:justify-start">
                                    <img src="/images/webmoney.svg" alt="webmoney">
                                    <img src="/images/qiwi.svg" alt="qiwi">
                                </div>
                                <n-input class="py-1.5 my-1 sm:!w-3/4" placeholder="Сумма, рублей" :theme-overrides="inputThemeOverrides"/>
                                <p class="py-4 text-violet-100 text-base font-normal font-['Open Sans']">Комиссия системы 15% (не менее 85.0 р.)<br/>Максимальная сумма пополнения 170 000 р. <br/>При необходимости пополниться на сумму больше, совершите несколько платежей.</p>
                                <div class="flex items-center py-2 gap-x-2.5 sm:items-baseline">
                                    <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                                    <p class="text-violet-100 sm:text-lg text-xs font-normal font-['Open Sans'] leading-normal">Привязывая карту, вы соглашаетесь <br/>с <span class="underline">правилами настройки автопополнения</span></p>
                                </div>
                                <button class="sm:w-3/4 w-full my-2 bg-purple-600 rounded-3xl py-2 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Пополнить</button>
                            </div>
                            <div v-show="activeTab === 'qr'" class="mt-5 px-4 qr sm:w-3/4 sm:px-0">
                                <h1 class="text-violet-100 sm:text-3xl sm:text-left text-center text-xl font-bold font-['Open Sans'] leading-10">Оплата через QR код</h1>
                                <n-input class="py-1.5 my-1 sm:!w-3/4" placeholder="Сумма, рублей" :theme-overrides="inputThemeOverrides"/>
                                <p class="py-4 text-violet-100 text-base font-normal font-['Open Sans']">Комиссия системы 15% (не менее 85.0 р.)<br/>Максимальная сумма пополнения 170 000 р. <br/>При необходимости пополниться на сумму больше, совершите несколько платежей.</p>
                                <div class="flex items-center py-2 gap-x-2.5 sm:items-baseline">
                                    <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                                    <p class="text-violet-100 sm:text-lg text-xs font-normal font-['Open Sans'] leading-normal">Привязывая карту, вы соглашаетесь <br/>с <span class="underline">правилами настройки автопополнения</span></p>
                                </div>
                                <button class="sm:w-3/4 w-full my-2 bg-purple-600 rounded-3xl py-2 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Пополнить</button>
                            </div>
                            <button class="sm:w-auto w-full mt-20 text-violet-100 text-lg font-normal font-['Open Sans'] underline">Посмотреть историю транзакций</button>
                        </div>
                    </n-tab-pane>
                    <n-tab-pane name="org">
                        <template #tab>
                            <button @click.prevent="activeButton = 'org'" :class="['tab-button', 'transition', 'text-violet-100', 'text-lg', 'font-bold', 'font-[\'Open Sans\']', 'leading-normal', activeButton === 'org' ? 'active' : '']">Юридическое лицо или ИП</button>
                        </template>
                        <div class="px-4 pb-12 org">
                            <div class="inline-block org_commission">
                                <div class="flex items-start">
                                    <img src="/images/information.svg" alt="information">
                                    <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                        Комиссия системы на ввод составляет 15%<br>
                                        Минимальная сумма пополнения 20000 р.</p>
                                </div>
                            </div>
                            <h1>Получить на счет в системе</h1>
                            <div class="mt-12 sm:w-2/4">
                                <h1 class="pb-12">Реквизиты юр.лица:</h1>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Плательщик"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Юридический адрес"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Физический адрес (для почтовой корреспонденции)"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="ИНН"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="КПП"/>
                            </div>
                            <div class="mt-12 sm:w-2/4">
                                <h1 class="pb-12">Банковские реквизиты:</h1>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Расчётный счёт"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Банк адрес"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Кор. счёт"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="БИК"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="КПП"/>
                            </div>
                            <div class="mt-12 sm:w-2/4">
                                <h1 class="pb-12">Контактная информация:</h1>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Контактный телефон"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Электронная почта"/>
                            </div>
                        </div>
                        <div class="px-4 sm:px-0">
                        <div class="flex items-center justify-between py-8">
                            <h1 class="text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10">Сумма к оплате:</h1>
                            <h1 class="text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10">0 руб.</h1>
                        </div>
                        <div class="attention">
                            <div class="flex items-start">
                                <img src="/images/information.svg" alt="information">
                                <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                    Обратите внимание! Компания не является плательщиком НДС. Средства будут зачислены на баланс в течение 1 - 3 банковских дней после оплаты.Работаем с ЭДО.
                                    Счет выставляется на основании Публичного договора-оферты.</p>
                            </div>
                        </div>
                        <div class="flex items-center py-8 gap-x-2.5">
                            <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                            <p class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">Сохранить информацию о счете</p>
                        </div>
                        <button class="sm:w-2/4 w-full py-2 bg-purple-600 rounded-full text-white text-lg font-bold font-['Open Sans'] leading-normal">Создать</button>
                        <p class="mt-8 text-violet-100 text-lg font-normal font-['Open Sans'] underline">Посмотреть историю транзакций</p>
                        </div>
                    </n-tab-pane>
                </n-tabs>
            </div>
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss">
.tab-button{
    border-radius: 100px;
    border: 1px solid  #6522D9;
    background: transparent;
    padding: 10px 25px;
    width: 100%;
    &:hover{
        background: #301864;
    }
}
.tab-button.active{
    border-radius: 100px;
    border: 1px solid  #6522D9;
    background: #301864;
    padding: 10px 25px;
}
.org{
    width: 100%;
    border-bottom: 1px solid rgba(234, 224, 255, 0.40);
    .org_commission{
        padding: 24px;
        border-radius: 20px;
        border: 1px dashed  #EAE0FF;
    }
    h1{
        color: #EAE0FF;
        font-family: Open Sans;
        font-size: 30px;
        font-style: normal;
        font-weight: 700;
        line-height: 130%; /* 39px */
        @media screen and (max-width: 640px){
            color: var(--White, #EAE0FF);
            text-align: center;
            font-family: Open Sans;
            font-size: 22px;
            font-style: normal;
            font-weight: 700;
            line-height: 130%; /* 28.6px */
        }
    }
}
.attention{
    padding: 24px;
    border-radius: 20px;
    border: 1px dashed  #EAE0FF;
}

.payment_types {
    .payment_types-cards {
        padding: 10px;
        .payment_types-card-wrapper {
            padding: 2px;
            background: #2F334B;
            transition: all .2s;
            border-radius: 20px;
            cursor: pointer;
            &:hover{
                background: linear-gradient(to bottom, #2712A5, #181856);
                box-shadow: 0 0 10px rgba(255,255,255,0.4);
            }
            .payment_types-card {
                padding: 40px 0;
                border-radius: 20px;
                background: #2F334B;
            }
        }
    }
}
</style>
