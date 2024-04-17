<script setup>
import MessengerLayout from "@/Layouts/MessengerLayout.vue";
import SearchBar from "@/Components/Dashboard/SearchBar.vue";
import {useConversationsStore} from "@/stores/ConversationsStore.js";
import MessengerUser from "@/Components/Messenger/MessengerUser.vue";
import MessageBox from "@/Components/Dashboard/MessageBox.vue";
import { computed, nextTick, ref, watchEffect } from "vue";
import {usePage} from "@inertiajs/vue3";
import { mdiArrowLeft } from "@mdi/js";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";

const store = useConversationsStore();
store.getConversations();

const inputMessage = ref('');
const page = usePage();
const authenticatedUserID = computed(() => page.props.auth.user.id);
const webSocket = initWebSocket(authenticatedUserID.value);

const getCurrentTime = () => {
    const now = new Date();
    const utcString = now.toUTCString();
    const timePart = utcString.split(' ')[4];
    const [hours, minutes] = timePart.split(':');
    return `${hours}:${minutes}`;
};

const addNewMessage = (message, username = page.props.auth.user.username, content_type) => {
    store.addNewMessage(message, username, getCurrentTime(), content_type);
};

const processIncomingMessage = (data) => {
    console.log(data)
    if (data.conversation_id === store.conversation_id) {
        addNewMessage(data.message, data.username, data.content_type);
    }
};
function initWebSocket(userID) {
    const socket = new WebSocket(`wss://${import.meta.env.VITE_APP_WEBSOCKETS_IP}:1915/?userid=${userID}`);

    socket.onmessage = (event) => {
        const data = JSON.parse(event.data);
        processIncomingMessage(data);
    };

    socket.onerror = (error) => {
        console.log(error);
    };

    return socket;
}

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = () => {
        const base64Image = reader.result;
        sendMessage(base64Image, 'image'); // Send image as base64 to server, and specify it is an 'image'
    };

    reader.readAsDataURL(file);
}

const sendMessage = (messageContent, messageType='text') => {
    if (!store.conversation_id) {
        return;
    }

    webSocket.send(JSON.stringify({
        auth_id: authenticatedUserID.value,
        conversation_id: store.conversation_id,
        message: messageContent,
        username: page.props.auth.user.username,
        type: 'chat_message',
        content_type: messageType,
    }));

    addNewMessage(messageContent, page.props.auth.user.username, messageType);

    inputMessage.value = '';
};

watchEffect(() => {
    if (store.conversation_id != null) {
        store.getConversationsMessages();
    }
});

const goBack = () => {
    store.goBack();
};

const vScrollBottom = {
    async updated(el) {
        // Let Vue finish rendering elements
        await nextTick();
        // Scroll to the bottom
        el.scrollTop = el.scrollHeight;
    },
};
const handleSearch = (search) => {
    console.log(search)
    store.getConversations(search);
}
</script>

<template>
    <MessengerLayout>
        <template #conversations>
            <div class="h-full">
                <div class="p-2">
                    <search-bar @search="handleSearch"/>
                </div>
                <div class="overflow-y-auto" style="max-height: calc(90vh - 129px - 60px)">
                    <messenger-user :chats="store.conversations"/>
                </div>
            </div>
        </template>
        <template #conversation_messages>
            <div v-if="store.conversation_id" class="h-full flex flex-col ">
                <div>
                    <button :class="{ 'hidden': store.showChat }" @click="goBack" class="back-button"><BaseIcon style="color: #B5BBDB" size="40" :path="mdiArrowLeft"/></button>
                </div>
                <div class="flex-grow overflow-y-auto flex flex-col gap-y-3 p-2" v-scroll-bottom>
                    <MessageBox v-for="message in store.conversationsMessages" :isImage="message.content_type === 'image' || !message.message" :text="message.message" :user-avatar="message.user.username" :created_at="message.created_at_time" :is-time-string="true"/>
                </div>

                <div class="footer">
                    <div class="conversation-panel">
                        <div class="conversation-panel__container">
                            <label class="file-uploader">
                                <input type="file"  @change="handleFileUpload" style="display: none;" />
                                <div class="conversation-panel__button panel-item btn-icon add-file-button">
                                    <img src="/images/file.svg"  alt="file">
                                </div>
                            </label>
                            <input v-model="inputMessage" @keydown.enter.prevent="sendMessage(inputMessage)" class="conversation-panel__input panel-item" placeholder="Ведите ваше сообщения"/>
                            <button @click.prevent="sendMessage(inputMessage)" class="conversation-panel__button panel-item btn-icon send-message-button">
                                <img src="/images/ic-2.svg" alt="send">
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="h-full flex flex-col justify-center">
                <p class="text-center py-6 text-gray-500 text-base font-normal font-['Open Sans'] leading-tight">Выберите чат</p>
                <div class="flex justify-center">
                    <img src="/images/chats.svg" alt="">
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
    flex-shrink: 0;
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
