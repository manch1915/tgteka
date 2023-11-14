<script setup>
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

</script>

<template>
    <AppLayout>
        <ProfileLayout>
            <div class="total_balance">
                <div class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Общий баланс</div>
                <div class="total_balance-card my-7 flex flex-col gap-y-4 justify-center items-center w-1/3 p-2.5">
                    <p class="text-violet-100 text-xl font-semibold font-['Open Sans']">Общий баланс</p>
                    <p class="text-violet-100 text-2xl font-normal font-['Open Sans']"><span class="text-3xl">0</span>руб.</p>
                    <div class="flex gap-x-4">
                        <div class="output flex  items-center flex-col gap-y-1 justify-between">
                            <img class="w-6" src="/images/output.svg" alt="">
                            <p class="text-violet-100 text-sm font-normal font-['Roboto'] leading-none">Вывод</p>
                            <p class="text-violet-100 text-sm font-normal font-['Roboto'] leading-none">0 руб.</p>
                        </div>
                        <div class="input flex items-center flex-col gap-y-1 justify-between">
                            <img class="w-6" src="/images/input.svg" alt="">
                            <p class="text-violet-100 text-sm font-normal font-['Roboto'] leading-none">Ввод</p>
                            <p class="text-violet-100 text-sm font-normal font-['Roboto'] leading-none">0 руб.</p>
                        </div>
                    </div>
                    <button class="w-full px-6 py-4 bg-purple-600 transition hover:bg-purple-800 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Пополнить</button>
                </div>
            </div>
            <div class="payment_method">
                <h2 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Способы оплаты</h2>
                <p class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">Сохраненные карты</p>
                <div class="cards w-3/4 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                    <div class="grid grid-cols-3">
                        <p>Карта</p>
                        <p>Карта по умолчанию</p>
                        <p>Настройка автопополнения</p>
                    </div>
                </div>
                <div>
                    <button class="px-6 py-4 bg-purple-600 transition hover:bg-purple-800 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Добавить способ оплаты</button>
                </div>
                <p class="text-violet-100 w-3/4 text-sm font-normal font-['Open Sans'] leading-tight">Чтобы привязать новую карту для автоматических платежей, выберите функцию "привязать карту" на странице пополнения баланса. Проставляя галочку, вы соглашаетесь с правилами автоматических платежей. Совершив первый платеж, карта будет привязана. Настройки автоматических платежей позволяют выбрать период: 1 раз в неделю или 1 раз в месяц. А также - выбрать сумму: 500р., 1000р., 5000р., 10000р., 50000р.</p>
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
    }
}
</style>
