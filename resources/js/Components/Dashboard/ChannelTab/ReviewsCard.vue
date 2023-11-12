<script setup>

import {NRate} from "naive-ui";
</script>

<template>
    <div class="reviews_card">
        <div class="reviews_card_main p-3">
            <div class="flex gap-x-2">
                <img class="w-6 rounded-full" src="/images/avatar.jpg" alt="">
                <p class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">ma****@******.ru</p>
            </div>
            <div class="py-4 border-b border-black border-opacity-60">
                <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Опубликовали вовремя. Все хорошо. Но для публикаций в таких больших канал нужны реально крутые креативы!</p>
            </div>
            <div class="flex justify-between pt-4">
                <p class="w-36 text-violet-100 text-xs font-normal font-['Poppins'] leading-none">29.05.2023 08:47</p>
                <n-rate value="3" readonly/>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.reviews_card{
    padding: 3px;
    background: linear-gradient(to right bottom, #151515, #5A5277, #1F304E);
    border-radius: 0px 25px 25px 25px;
    .reviews_card_main{
        background: linear-gradient(to right bottom,#9A9CAC, #595D78);
        border-radius: 0px 25px 25px 25px;
    }
}
</style>
