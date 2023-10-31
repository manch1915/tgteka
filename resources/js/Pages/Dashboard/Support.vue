<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";

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
        type: 'support'
    }));
    console.log(userId.value)
}

socket.onmessage = function(event) {
    console.log(`[message] Данные получены с сервера: ${event.data}`);
};

socket.onclose = function(event) {
    if (event.wasClean) {
        console.log(`[close] Соединение закрыто чисто, код=${event.code} причина=${event.reason}`);
    } else {
        // например, сервер убил процесс или сеть недоступна
        // обычно в этом случае event.code 1006
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

            </div>
        </div>
        <hr class="mt-40">
    </AppLayout>
</template>

<style scoped lang="scss">
.grid {
    grid-template-columns: 4fr 6fr;
}
</style>
