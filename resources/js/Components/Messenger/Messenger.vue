<script setup>

import MessengerLayout from "@/Layouts/MessengerLayout.vue";
import SearchBar from "@/Components/Dashboard/SearchBar.vue";
import {useConversationsStore} from "@/stores/ConversationsStore.js";
import MessengerUser from "@/Components/Messenger/MessengerUser.vue";
import MessageBox from "@/Components/Dashboard/MessageBox.vue";
import {computed, ref, watchEffect} from "vue";
import {usePage} from "@inertiajs/vue3";

const conversations = useConversationsStore();

conversations.getConversations();

const message = ref('');

const page = usePage()

const userId = computed(() => page.props.auth.user.id)
const socket = new WebSocket(`ws://localhost:1915?userid=${userId.value}`);

const getCurrTime = () => {
    const now = new Date();
    const utcString = now.toUTCString(); // Generates UTC string
    const timePart = utcString.split(' ')[4]; // Extracts the 'HH:mm:ss' part
    const [ hours, minutes ] = timePart.split(':'); // Breaks 'HH:mm:ss' into [ 'HH', 'mm', 'ss' ]
    return `${hours}:${minutes}`; // Returns 'HH:mm'
};

socket.onerror = function(error) {
    console.log(error);
};

const sendMessage = () => {
    if (!conversations.conversation_id) {
        return;
    }
    socket.send(JSON.stringify({
        auth_id: userId.value,
        conversation_id: conversations.conversation_id,
        message: message.value,
        type: 'personal'
    }));

    conversations.addNewMessage(
        message.value,
        `https://ui-avatars.com/api/?name=${userId.value}&color=7F9CF5&background=EBF4FF`,
        getCurrTime()
    );
    message.value = ''
}
socket.onmessage = function(event) {
    const data = JSON.parse(event.data);

    if(data.conversation_id === conversations.conversation_id) {
        conversations.addNewMessage(
            data.message,
            `https://ui-avatars.com/api/?name=${data.sender_id}&color=7F9CF5&background=EBF4FF`,
            getCurrTime()
        )
    }
};
watchEffect(() => {
    if (conversations.conversation_id != null) {
        conversations.getConversationsMessages();
    }
});

</script>

<template>
    <MessengerLayout>
        <template #conversations>
            <div class="h-full">
                <div class="p-2">
                    <search-bar @search="handleSearch"/>
                </div>
                <div class="overflow-y-auto" style="max-height: calc(90vh - 129px - 60px)">
                    <messenger-user :chats="conversations.conversations"/>
                </div>
            </div>
        </template>
        <template #conversation_messages>
            <div v-if="conversations.conversation_id" class="h-full flex flex-col">
                <div class="flex flex-col gap-y-3" style=" overflow-y: auto">
                    <MessageBox v-for="message in conversations.conversationsMessages" :text="message.message" :user-avatar="message.user.profile_photo_url" :created_at="message.created_at_time"/>
                </div>
                <div class="footer">
                    <div class="conversation-panel">
                        <div class="conversation-panel__container">
                            <button class="conversation-panel__button panel-item btn-icon add-file-button">
                                <img src="/images/file.svg" alt="file">
                            </button>
                            <input v-model="message" class="conversation-panel__input panel-item" placeholder="Ведите ваше сообщения"/>
                            <button @click.prevent="sendMessage" class="conversation-panel__button panel-item btn-icon send-message-button">
                                <img src="/images/ic-2.svg" alt="send">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </MessengerLayout>
</template>

<style scoped lang="scss">
.flex{
    background: #0D143A;
}
.footer{
    height: 10%;
    border-radius:0 0 30px 30px ;
    padding: 12px 20px;
    background: rgba(42, 47, 77, 1);
    .conversation-panel {
        background: rgba(13, 20, 58, 1);
        border-radius: 24px;
        border: 1px solid #A8A8A8;
        padding: 0.4em 1em;

        &__container {
            display: flex;
            flex-direction: row;
            align-items: center;
            height: 100%;

        }
        &__button {
            background: grey;
            height: 20px;
            width: 30px;
            border: 0;
            padding: 0;
            outline: none;
            cursor: pointer;
        }
        .add-file-button {
            height: 23px;
            min-width: 23px;
            width: 23px;
            background: var(--chat-add-button-background);
            border-radius: 50%;
            svg {
                width: 70%;
            }
        }
        .send-message-button {
            background: var(--chat-send-button-background);
            height: 30px;
            min-width: 30px;
            border-radius: 50%;
            transition: .3s ease;
            &:active {
                transform: scale(.97);
            }
            svg {
                margin: 1px -1px;
            }
        }
        &__input {
            width: 100%;
            height: 100%;
            outline: none;
            position: relative;
            color: #EAE0FF;
            font-size: 13px;
            background: transparent;
            border: 0;
            font-family: 'Lato', sans-serif;
            resize: none;
            &:focus{
                border-color: inherit;
                -webkit-box-shadow: none;
                box-shadow: none;
            }
        }
    }
}
</style>
