<script setup>
import {useConversationsStore} from "@/stores/ConversationsStore.js";

const props = defineProps({
    chats: Object,
})
const getAvatarUrl = (username) => `https://api.dicebear.com/7.x/initials/svg?seed=${username}`;
const conversations = useConversationsStore()

const openChat = (chatId) => {
    conversations.openChat();
    conversations.conversation_id = chatId
};
</script>

<template>
    <div @click.prevent="openChat(chat.id)" class="messenger-user py-2 cursor-pointer" v-for="chat in chats">
        <div class="flex gap-x-3 px-2">
            <div class="avatar">
                <img :src="getAvatarUrl(chat.user.username)" alt="">
            </div>
            <div class="flex flex-col justify-around overflow-hidden">
                <p class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">{{chat.user.username}}</p>
                <p class="text-gray-100 opacity-40 text-sm font-normal font-['Open Sans'] leading-snug">
                    {{ chat?.last_message ? chat.last_message.message : 'пусто' }}
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.messenger-user{
    background: #0D143A;
    &:hover{
        background: #222c5f;
    }
}
.avatar{
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        display: block;
    }
}
</style>
