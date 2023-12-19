<script setup>
import {useConversationsStore} from "@/stores/ConversationsStore.js";

const props = defineProps({
    chats: Object,
})
const getAvatarUrl = (name) => `https://api.dicebear.com/7.x/initials/svg?seed=${name}`;
const conversations = useConversationsStore()
</script>

<template>
    <div @click.prevent="conversations.conversation_id = chat.id" class="messenger-user py-2 cursor-pointer" v-for="chat in chats">
        <div class="flex gap-x-3 px-2">
            <div class="avatar">
                <img :src="getAvatarUrl(chat.user.name)" alt="">
            </div>
            <div class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">
                {{chat.user.name}}
                <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-snug">{{ chat.last_message.message }}</p>
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
    width: 50px;
    img{
        border-radius: 50%;
    }
}
</style>
