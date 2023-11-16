<script setup>
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

</script>

<template>
    <AppLayout>
        <ProfileLayout>
            <div class="px-4 total_balance sm:p-0">
                <div class="text-center sm:text-left">
                    <p class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Общий баланс</p>
                </div>
                <div class="my-7 flex flex-col items-center justify-center gap-y-4 total_balance-card p-2.5 sm:w-1/3">
                    <p class="text-violet-100 text-xl font-semibold font-['Open Sans']">Общий баланс</p>
                    <p class="text-violet-100 text-2xl font-normal font-['Open Sans']"><span class="text-3xl">0</span>руб.</p>
                    <div class="flex gap-x-4">
                        <div class="flex flex-col items-center justify-between gap-y-1 output">
                            <img class="w-6" src="/images/output.svg" alt="">
                            <p class="text-violet-100 text-sm font-normal font-['Roboto'] leading-none">Вывод</p>
                            <p class="text-violet-100 text-sm font-normal font-['Roboto'] leading-none">0 руб.</p>
                        </div>
                        <div class="flex flex-col items-center justify-between gap-y-1 input">
                            <img class="w-6" src="/images/input.svg" alt="">
                            <p class="text-violet-100 text-sm font-normal font-['Roboto'] leading-none">Ввод</p>
                            <p class="text-violet-100 text-sm font-normal font-['Roboto'] leading-none">0 руб.</p>
                        </div>
                    </div>
                    <button class="w-full px-6 py-4 bg-purple-600 transition hover:bg-purple-800 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Пополнить</button>
                </div>
            </div>
            <div class="px-4 payment_method sm:p-0">
                <h2 class="text-violet-100 sm:text-left text-center sm:text-4xl text-xl font-bold font-['Open Sans'] leading-10">Способы оплаты</h2>
                <p class="text-violet-100 sm:text-left text-center text-xl font-normal font-['Open Sans'] leading-relaxed">Сохраненные карты</p>
                <div class="cards sm:w-3/4 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                    <div class="grid grid-cols-1 sm:grid-cols-3">
                        <p>Карта</p>
                        <p>Карта по умолчанию</p>
                        <p>Настройка автопополнения</p>
                    </div>
                </div>
                <div>
                    <button class="sm:w-auto w-full px-6 py-4 bg-purple-600 transition hover:bg-purple-800 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Добавить способ оплаты</button>
                </div>
                <p class="text-violet-100 sm:w-3/4 text-sm font-normal font-['Open Sans'] leading-tight">Чтобы привязать новую карту для автоматических платежей, выберите функцию "привязать карту" на странице пополнения баланса. Проставляя галочку, вы соглашаетесь с правилами автоматических платежей. Совершив первый платеж, карта будет привязана. Настройки автоматических платежей позволяют выбрать период: 1 раз в неделю или 1 раз в месяц. А также - выбрать сумму: 500р., 1000р., 5000р., 10000р., 50000р.</p>
            </div>
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss">
.total_balance-card {
    border-radius: 30px;
    background: #2F334B;
}
.payment_method > *:not(:last-child){
    padding-bottom: 31px;
}
.payment_method{
    .cards{
        p{
            border-right: 1px solid #EAE0FF;
        }
        .grid{
            p:not(:last-child){
                margin-right: 29px;
            }
        }
        @media screen and (max-width: 640px){
            p{
                border-right: 0px;
                border-bottom: 1px solid #EAE0FF;
            }
            .grid{
                p{
                    padding-top: 20px;
                    padding-bottom: 10px;
                    margin-right: 0 !important;
                }
            }
        }
    }
}
</style>
