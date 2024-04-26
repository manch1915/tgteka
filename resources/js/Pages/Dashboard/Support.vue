<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import { computed, ref } from "vue";
import {usePage, Head, router} from "@inertiajs/vue3";
import { openModal } from "jenesius-vue-modal";
import ModalAlert from "@/Components/Dashboard/ModalAlert.vue";
import TicketCard from "@/Components/Dashboard/TicketCard.vue";
import Messanger from "@/Components/Dashboard/Messanger.vue";
import {NUpload, useMessage} from "naive-ui";

const props = defineProps({
    tickets: Object,
    ticketsCount: Number,
});

const openMessengerModal = (ticketId, ticketTitle) => {
    openModal(Messanger, {
        tickets: ticketId,
        socket: socket,
        userId: userId.value,
        ticketTitle: ticketTitle,
    });
};

const page = usePage();

const message = useMessage();

const userId = computed(() => page.props.auth.user.id);

const socket = new WebSocket(
    `wss://${import.meta.env.VITE_APP_WEBSOCKETS_IP}:1915/?userid=${
        userId.value
    }`
);

const title = ref("");
const content = ref("");

const uploadedFile = ref(null);
const upload = ref(null);

const handleChange = (data) => {
    uploadedFile.value = data.file.file;
}

const handleRemove = () => {
    uploadedFile.value = null;
}

const createNewAppeal = () => {
    if (socket.readyState !== WebSocket.OPEN) {
        // If the connection is not open, display an error message and return
        message.error("Ошибка соединения с сервером WebSocket.");
        return;
    }
    if (title.value.trim() === "" && content.value.trim() === "") {
        message.error("Заполните 'Тема обращения' и 'Текст обращения' ");
        return;
    }
    socket.send(
        JSON.stringify({
            title: title.value,
            message: content.value,
            sender_id: userId.value,
            ticket_id: null,
            type: "support_message",
            content_type: "text",
        })
    );

    const handleResponse = (ticket_id) => {
        if (ticket_id) {
            const reader = new FileReader();
            reader.onload = function(event) {
                // Send the image message with the base64 data
                socket.send(
                    JSON.stringify({
                        title: '',
                        message: event.target.result, // Base64 data
                        sender_id: userId.value,
                        ticket_id: ticket_id,
                        type: "support_message",
                        content_type: "image",
                    })
                );
            };
            reader.readAsDataURL(uploadedFile.value);
            uploadedFile.value = null;
            upload.value?.clear()

        } else {
            message.error("Не получен идентификатор заявки от сервера.");
        }
    };

    socket.onmessage = function (event) {
        const data = JSON.parse(event.data)

        if (uploadedFile.value !== null && data.type == 10) {
            handleResponse(data.ticket_id)
        }

    }

    title.value = ""
    content.value = ""
    openModal(ModalAlert);
    router.reload();
};

socket.onclose = function (event) {
    if (event.wasClean) {
        console.log(
            `[close] Соединение закрыто чисто, код=${event.code} причина=${event.reason}`
        );
    } else {
        console.log("[close] Соединение прервано");
    }
};

socket.onerror = function (error) {
    console.log(error);
};
</script>

<template>
    <Head>
        <title>Поддержка</title>
    </Head>
    <AppLayout>
        <div class="mt-12 grid support_grid">
            <div class="sm:p-6">
                <h1
                    class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10"
                >
                    Обратная связь
                </h1>
                <p
                    class="mt-12 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                >
                    Заполните форму обратной связи, если у вас возникли вопросы
                    или проблемы
                </p>
                <div class="mt-12 flex flex-col gap-y-6">
                    <TextInput placeholder="Тема обращения" v-model="title" />
                    <TextArea
                        placeholder="Текст обращения"
                        rows="4"
                        v-model="content"
                    />
                    <n-upload
                        :default-upload="false"
                        @change="handleChange"
                        :on-remove="handleRemove"
                        :max="1"
                        ref="upload"
                        accept="image/jpeg,image/jpg,image/png"
                    >
                        <button class="select-none main_button sm:text-lg text-base font-bold text-paleblue text-center flex gap-x-2 items-center"><img
                            src="/images/paper-clip.svg" alt="">Прикрепить фото</button>
                    </n-upload>
                </div>

                <button
                    @click.prevent="createNewAppeal"
                    class="flex my-2 justify-center w-full py-4 btn_gradient-purple transition hover:bg-purple-800 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                >
                    Отправить
                </button>
            </div>
            <div
                class="flex w-full rounded-3xl bg-opacity-30 p-4 bg-violet-950"
            >
                <div
                    v-if="!tickets"
                    class="flex h-full w-full flex-col items-center justify-center"
                >
                    <div
                        class="flex flex-col items-center justify-center gap-y-10"
                    >
                        <p
                            class="text-center text-violet-100 text-2xl font-normal font-['Open Sans'] leading-tight"
                        >
                            Обращений ещё нет
                        </p>
                        <img src="/images/ic.svg" alt="ic" />
                    </div>
                </div>
                <div class="w-full p-6" v-else>
                    <p
                        class="text-violet-100 text-right text-base font-normal font-['Open Sans'] leading-tight"
                    >
                        {{ ticketsCount }} обращений
                    </p>

                    <div class="mt-6 flex h-full flex-col gap-y-4">
                        <TicketCard
                            v-for="ticket in tickets"
                            :key="ticket.id"
                            :title="ticket.title"
                            :created_at="ticket.localized_date"
                            @click.prevent="
                                openMessengerModal(ticket.id, ticket.title)
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
        <hr class="mt-40" />
        <div class="mt-40 px-12 contacts">
            <h1
                class="text-violet-100 lg:text-4xl text-xl font-bold font-['Open Sans'] leading-10"
            >
                Контакты
            </h1>
            <div
                class="mt-20 grid grid-cols-1 items-center justify-start sm:grid-cols-2"
            >
                <div class="flex flex-col gap-y-2">
                    <h1
                        class="text-violet-100 lg:text-4xl text-xl font-bold font-['Open Sans'] leading-10"
                    >
                        Позвонить:
                    </h1>
                    <p
                        class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed"
                    >
                        Отдел продаж
                    </p>
                    <p
                        class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"
                    >
                        8 (800) 707-63-15
                    </p>
                    <p
                        class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"
                    >
                        +7 (495) 818-62-50
                    </p>
                </div>
                <div class="flex flex-col gap-y-2">
                    <h1
                        class="text-violet-100 lg:text-4xl text-xl font-bold font-['Open Sans'] leading-10"
                    >
                        Написать:
                    </h1>
                    <p
                        class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed"
                    >
                        Бот
                    </p>
                    <p
                        class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"
                    >
                        @telegamain_bot
                    </p>
                    <p
                        class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"
                    >
                        @telegain
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">
.support_grid {
    grid-template-columns: 4fr 6fr;
    @media screen and (max-width: 640px) {
        grid-template-columns: 1fr;
    }
}
.main_button {
    padding: 10px 18px;
    border-radius: 100px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: #171961;
    box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25) inset;
}
</style>
