<script setup>
import {closeModal} from "jenesius-vue-modal";
import { useConversationsStore } from "@/stores/ConversationsStore.js";

const conversations = useConversationsStore()
let isSmallScreen = window.innerWidth <= 768;

</script>

<template>
    <div class="wrapper container">
        <div class="h-full flex flex-col">
            <div class="top header">
                <div class="flex items-center w-full">
                    <div class="flex-1"></div>
                    <div class="flex flex-1 flex-col justify-center sm:items-center items-start gap-y-6">
                        <h1 class="sm:text-center text-start text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10">Чат</h1>
                    </div>
                    <div class="flex-1 flex justify-end cursor-pointer" @click.prevent="closeModal()">
                        <img class="messenger_close" src="/images/Icon-close.svg" alt="">
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="conversations m-2 mt-0 mr-0 py-2" :class="{ 'hidden': !conversations.showChat && isSmallScreen }">
                    <slot name="conversations"/>
                </div>
                <div class="message m-2 mt-0 ml-0 rounded-br-2xl" :class="{ 'hidden': conversations.showChat && isSmallScreen }">
                    <slot name="conversation_messages"/>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wrapper{
    height: 90vh;
    border: 1px solid #6522D9;
    background: #070C29;
    border-radius: 0 20px 20px 20px;

}
.top{
    padding: 20px 65px;
    border-radius: 30px 30px 0 0;
    background: rgba(7, 12, 41, 1);
    box-sizing: border-box;
    @media screen and (max-width: 640px){
        padding: 10px;
    }
}
.content {
    display: flex;
    flex-direction: row;
    height: 90%;
    column-gap: 5px;
}
.conversations, .message {
    flex-basis: 50%;
    overflow-y: auto;
    @media screen and (max-width: 768px){
        flex-basis: 100%;
    }

}
.conversations{
    background: #0D143A;
    border-radius: 0 20px 0 20px;
}
.messenger_close{
    transition: all .5s;
    &:hover{
        filter: drop-shadow(0 0 8px #9d9d9d) ;
    }
}
</style>
