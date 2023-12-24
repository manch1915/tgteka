<script setup>
import {closeModal} from "jenesius-vue-modal";
</script>

<template>
    <div class="wrapper container">
        <div class="h-full flex flex-col">
        <div class="top">
            <div class="header flex items-center w-full">
                <div class="flex-1"></div>
                <div class="flex flex-1 flex-col justify-center sm:items-center items-start gap-y-6">
                    <h1 class="sm:text-center text-start text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10">Чат</h1>
                </div>
                <div class="flex-1 flex justify-end cursor-pointer" @click.prevent="closeModal()">
                    <img src="/images/Icon-close.svg" alt="">
                </div>
            </div>
        </div>
        <div class="flex-1 grid sm:grid-cols-2 grid-cols-1" style="height: calc(90vh - 124px)">
            <div class="flex-1 conversations py-2">
                <slot name="conversations"/>
            </div>
            <div class="flex-1">
                <slot name="conversation_messages"/>
            </div>
        </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wrapper{
    border: 1px solid #6522D9;
    background: #070C29;
    height: 90vh;
    border-radius: 0 20px 20px 20px;
}
.top{
    padding: 65px 65px 20px 65px;
    border-radius: 30px 30px 0 0;
    background: rgba(7, 12, 41, 1);

    @media screen and (max-width: 640px){
        padding: 10px;
        height: 70vh;
    }
    .overflowing{
        overflow-y: auto;
        height: calc(100% - 35px);
        @media screen and (max-width: 640px){
            height: calc(100% - 75px);
        }
    }
}
.conversations{
    background: #0D143A;
    border-radius: 0 20px 0 20px;
    height: 100%;
}
</style>
