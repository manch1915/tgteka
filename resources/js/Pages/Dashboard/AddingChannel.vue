<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import FormSelect from "@/Components/FormSelect.vue";
import RadioGroup from "@/Components/RadioGroup.vue";
import {ref} from "vue";

const radio = ref('')

</script>

<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto text-center">
            <h1 class="py-24 text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Добавление канала /
                чата</h1>
            <div class="px-24 avatar flex justify-center">
                <div class="rounded-full w-24 h-24 border-2">

                </div>
                <div class="pl-12">
                    <button
                        class="w-full px-6 py-3.5 bg-purple-600 rounded-3xl text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        Загрузить фото канала/ чата
                    </button>
                    <p class="text-start pt-5 text-violet-100 text-opacity-40 text-base font-normal font-['Inter'] leading-tight">
                        Формат изображения jpg, jpeg, png, не менее 140*140рх,не более 2800*2024рх</p>
                </div>
            </div>
            <div class="flex flex-col gap-y-16">
                <div class="w-full text-start flex flex-col gap-y-3">
                    <h2 class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Название
                        канала/чата</h2>
                    <text-input
                        id="name"
                        type="text"
                        class="w-full py-3"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Иванов Иван"/>
                </div>
                <div class="w-full text-start flex flex-col gap-y-3">
                    <h2 class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Описание канала/чата</h2>
                    <text-area
                        id="name"
                        type="textarea"
                        class="w-full py-3"
                        required
                        autofocus
                        autocomplete="description"
                        placeholder="Опишите особенности вашего канала, которые выделяют вас в каталоге. Не указывать личные контакты или ссылки на другие сайты."/>
                </div>
                <div class="w-full text-start flex flex-col gap-y-3">
                    <h2 class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Тематика канала/чата</h2>
                    <form-select
                        id="name"
                        class="w-full py-3"
                        required
                        autofocus
                        autocomplete="subject">
                        выберите тему канала/чата
                    </form-select>
                </div>
                <div class="w-full text-center flex flex-col gap-y-3">
                    <h2 class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Вы добавляете</h2>
                    <RadioGroup :values="['channel' , 'chat']" v-model="radio" />
                </div>
                <div class="w-full text-start flex flex-col gap-y-3">
                    <h2 class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Ссылка на канал/чат</h2>
                    <text-input
                        id="name"
                        type="text"
                        class="w-full py-3"
                        required
                        autofocus
                        autocomplete="channel"
                        placeholder="@channel или https://t.me/dr_amina_pirmanova"/>
                </div>
                <div class="w-full text-start flex flex-col gap-y-3">
                    <h2 class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Язык</h2>
                    <form-select
                        id="name"
                        class="w-full py-3"
                        required
                        autofocus
                        autocomplete="language">
                        <!--           TODO get selected first option from data array if slot is empty         -->
                    </form-select>
                </div>
            </div>
        </div>
        <div class="max-w-2xl mx-auto text-center">
            <div class="flex flex-col gap-y-16">
                <div>
                    <div class="w-full flex flex-col gap-y-3">
                        <h2 class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Источник
                            подписчиков</h2>
                        <text-area
                            id="name"
                            type="textarea"
                            class="w-full py-3"
                            required
                            autofocus
                            autocomplete="description"
                            placeholder="Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации."/>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">

</style>
