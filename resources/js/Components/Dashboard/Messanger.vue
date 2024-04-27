<script setup>
import {closeModal} from "jenesius-vue-modal";
import MessageBox from "@/Components/Dashboard/MessageBox.vue";
import {onMounted, ref} from "vue";

const props = defineProps({
    tickets: Number,
    socket: Object,
    userId: Number,
    ticketTitle: String
})

const messages = ref(null)
const message = ref('')
const messageContainer = ref(null);

const scrollToBottom = () => {
    // Check if message container is available
    if (messageContainer.value) {
        // Scroll to the bottom
        messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
    }
}

const getMessages =  async () => {
    await axios.post(route('get-messages-by-ticket-id'), {tickets: props.tickets}).then(res => {
        messages.value = res.data;
    });
}

onMounted(()=> getMessages())

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
    props.socket.send(JSON.stringify({
        title: '',
        message: messageContent,
        sender_id: props.userId,
        ticket_id: props.tickets,
        type: 'support_message',
        content_type: messageType // New field 'content_type' to specify the type of content that is being sent
    }));

    messages.value.push({
        message: messageContent,
        sender: {
            profile_photo_url: `https://ui-avatars.com/api/?name=${props.userId}&color=7F9CF5&background=EBF4FF`
        },
        created_at:"+055809-07-05T06:33:20.000000Z",
        content_type: messageType
    });
    message.value = '';

    scrollToBottom();
}

props.socket.onmessage = function(event) {
    const data = JSON.parse(event.data);

    if(data.ticket_id === props.tickets && data.sender_id !== props.userId) {
        messages.value.push({
            message: data.message,
            sender: {
                username: data.sender_id
            },
            created_at: new Date().toISOString(),
            content_type: data.content_type
        });
    }
    scrollToBottom();
};
</script>

<template>
    <div class="container">
    <main>
        <div class="top">
            <div class="header border-b mb-2 pb-2">
                <div class="float-right cursor-pointer" @click.prevent="closeModal()">
                    <img src="/images/Icon-close.svg" alt="">
                </div>
                <div class="flex flex-col justify-center sm:items-center items-start gap-y-6">
                    <h1 class="sm:text-center text-start text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10">{{ ticketTitle }}</h1>
                </div>
            </div>
            <div class="flex flex-col gap-y-3 overflowing p-2" ref="messageContainer">
                <MessageBox v-for="message in messages" :isImage="message.content_type === 'image' || !message.message" :text="message.message" :user-avatar="message.sender.username" :created_at="message.created_at"/>
            </div>
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
                    <input v-model="message" @keydown.enter.prevent="sendMessage(message)" class="conversation-panel__input panel-item" placeholder="Ведите ваше сообщения"/>
                    <button @click.prevent="sendMessage(message)" class="conversation-panel__button panel-item btn-icon send-message-button">
                        <img src="/images/ic-2.svg" alt="send">
                    </button>
                </div>
            </div>
        </div>
    </main>
    </div>
</template>

<style scoped lang="scss">
main{
    width: 100%;
    border-radius: 30px 30px;
    border: 1px solid rgba(101, 34, 217, 1);
    /* Profile/Shadow */
    box-shadow: 0 3px 40px 0 rgba(41, 37, 0, 0.10);
}
.top{
    padding: 65px;
    border-radius: 30px 30px 0 0;
    background: rgba(7, 12, 41, 1);
    height: 80vh;

    @media screen and (max-width: 640px){
        padding: 10px;
        height: 70vh;
    }
    .header{}
    .overflowing{
        overflow-y: auto;
        height: calc(100% - 35px);
        @media screen and (max-width: 640px){
            height: calc(100% - 75px);
        }
    }
}

.footer{
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
