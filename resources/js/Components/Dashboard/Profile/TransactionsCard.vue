<script setup>
const props = defineProps({
    transaction: Object
})
</script>

<template>
    <div class="sm:p-0 p-5">
    <div class="wrapper">
        <div class="wrapper_container grid sm:grid-cols-5 grid-cols-1 leading-tight" style="word-break: break-word">
            <div class="flex flex-col">
                <div class="py-6 border sm:rounded-tr-0 rounded-tr-[30px] sm:rounded-tr-[0px]"><h2 class="title text-center">Дата и время</h2></div>
                <div class="p-2 border flex items-center justify-center h-20 sm:rounded-bl-[30px]"><p class="subtitle text-center">{{transaction.created_at}}</p></div>
            </div>
            <div class="flex flex-col">
                <div class="py-6 border"><h2 class="title text-center">Услуги</h2></div>
                <div class="p-2 border flex items-center justify-center h-20"><p class="subtitle text-center">{{transaction.appointment}}</p></div>
            </div>
            <div class="flex flex-col">
                <div class="py-6 border"><h2 class="title text-center">Назначение</h2></div>
                <div class="p-2 border flex items-center justify-center h-20"><p class="subtitle text-center">{{transaction.service}}</p></div>
            </div>
            <div class="flex flex-col">
                <div class="py-6 border"><h2 class="title text-center">Сумма</h2></div>
                <div class="p-2 border flex items-center justify-center h-20"><p class="subtitle text-center">{{transaction.amount}}</p></div>
            </div>
            <div class="flex flex-col">
                <div class="py-6 border sm:rounded-tr-[30px]"><h2 class="title text-center">Статус</h2></div>
                <div class="p-2 border flex items-center justify-center h-20 sm:rounded-br-[30px] rounded-b-[30px] sm:rounded-b-[0px]"> <p class="subtitle text-center text-lg"
                                                                                                      :class="{ '!text-red-500': transaction.status === 'Неуспешный', '!text-green-500': transaction.status === 'успешный' }">
                    {{ transaction.status }}
                </p></div>
            </div>
        </div>
    </div>
    </div>
</template>

<style scoped lang="scss">
.title{
    color:  #EAE0FF;
    font-family: Open Sans;
    font-size: 14px;
    font-style: normal;
    font-weight: 700;
    line-height: 1;
}
.subtitle{
    color: #EAE0FF;
    font-family: Open Sans;
    font-size: 13px;
    font-style: normal;
    font-weight: 400;
    line-height: 1;
}
.wrapper{
    padding: 2px;
    background: linear-gradient(to bottom, #665D8F, #1C1C59);
    border-radius: 0 30px 30px 30px;
}
.wrapper_container{
    border-radius: 0 30px 30px 30px;
    background: linear-gradient(to bottom, #141834, #44485E);
}
</style>
