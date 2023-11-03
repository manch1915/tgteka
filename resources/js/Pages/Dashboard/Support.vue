<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import {openModal} from "jenesius-vue-modal";
import ModalAlert from "@/Components/Dashboard/ModalAlert.vue";
import TicketCard from "@/Components/Dashboard/TicketCard.vue";
import Messanger from "@/Components/Dashboard/Messanger.vue";

const props = defineProps({
    tickets: Object,
    ticketsCount: Number,
})

const openMessengerModal = (ticketId) =>{
    //todo messenger
    openModal(Messanger, {tickets: ticketId, socket:socket, userId: userId.value})
}

const page = usePage()

const userId = computed(() => page.props.auth.user.id)

const socket = new WebSocket('ws://localhost:8080');
const title = ref('');
const content = ref('');

const createNewAppeal = () => {
    socket.send(JSON.stringify({
        title: title.value,
        message: content.value,
        sender_id: userId.value,
        ticket_id: null,
        type: 'support'
    }));

    openModal(ModalAlert)
}

socket.onmessage = function(event) {
    console.log(`[message] Данные получены с сервера: ${event.data}`);
};

socket.onclose = function(event) {
    if (event.wasClean) {
        console.log(`[close] Соединение закрыто чисто, код=${event.code} причина=${event.reason}`);
    } else {
        console.log('[close] Соединение прервано');
    }
};

socket.onerror = function(error) {
    console.log(`[error]`);
};
</script>

<template>
    <AppLayout>
        <div class="grid mt-12">
            <div class="p-6">
                <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Обратная связь</h1>
                <p class="mt-12 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Заполните форму обратной связи, если у вас возникли вопросы или проблемы</p>
                <div class="flex flex-col gap-y-6 mt-12">
                    <TextInput
                        placeholder="Тема обращения"
                        v-model="title"
                    />
                    <TextArea
                        placeholder="Тема обращения"
                        rows="4"
                        v-model="content"
                    />
                </div>
                <p class="my-4 text-violet-100 text-opacity-40 text-base font-normal font-['Inter'] leading-tight">Формат изображения jpg, jpeg, png, <br/>не менее 140*140рх,не более 1600*1024рх</p>
                <button @click.prevent="createNewAppeal" class="flex justify-center w-full py-4 bg-purple-600 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                    Отправить
                </button>
            </div>
            <div class="p-4 flex h-full w-full bg-violet-950 bg-opacity-30 rounded-3xl">
                <div v-if="!tickets" class="h-full w-full flex flex-col items-center justify-center">
                    <div class="flex flex-col items-center justify-center gap-y-10">
                        <p class="text-center text-violet-100 text-2xl font-normal font-['Open Sans'] leading-tight">Обращений ещё нет</p>
                        <img src="/images/ic.svg" alt="ic">
                    </div>
                </div>
                <div class="w-full p-6" v-else>
                    <p class="text-violet-100 text-right text-base font-normal font-['Open Sans'] leading-tight">{{ticketsCount}} обращений</p>

                    <div class="flex flex-col h-full gap-y-4 mt-6">
                        <TicketCard v-for="ticket in tickets" :key="ticket.id" :title="ticket.title" :created_at="ticket.created_at" @click.prevent="openMessengerModal(ticket.id)"/>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mt-40">
        <div class="contacts mt-40 px-12">
            <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Контакты</h1>
            <div class="grid grid-cols-2 justify-start items-center mt-20">
                <div class="flex flex-col gap-y-2">
                    <h1 class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">Позвонить:</h1>
                    <p class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">Отдел продаж</p>
                    <p class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">8 (800) 707-63-15</p>
                    <p class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">+7 (495) 818-62-50</p>
                </div>
                <div class="flex flex-col gap-y-2">
                    <h1 class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">Написать:</h1>
                    <p class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">Бот</p>
                    <p class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">@telegamain_bot</p>
                    <p class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">@telegain</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">
.grid {
    grid-template-columns: 4fr 6fr;
}

</style>
