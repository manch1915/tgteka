<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import {NCheckbox, NSelect, NSwitch, useLoadingBar} from 'naive-ui';
import {
    switchThemeOverrides,
    checkboxThemeOverrides,
    selectThemeOverrides,
    checkboxToRadioThemeOverrides,
} from '@/themeOverrides.js';
import {reactive, ref, toRefs, watch, watchEffect} from 'vue';
import {router} from "@inertiajs/vue3";

const props = defineProps({
    channelId: [Number, null],
    channel: Object,
    channelAvatar: [String, null]
})


const discount_check = ref(false);
const format_one_checkbox = ref(false);
const format_two_checkbox = ref(false);
const format_three_checkbox = ref(false);
const no_deletion_checkbox = ref(false);

const file = ref(null)

const form = reactive({
    avatar: null,
    channel_name: '',
    description: '',
    topic: '',
    type: '',
    channel_url: '',
    language: '',
    subscribers_source: '',
    repeat_discount: null,
    terms: false,
    format_one: 0,
    format_two: 0,
    format_three: 0,
    no_deletion: 0,
    '_method': 'patch'
});

watchEffect(() => {
    if (props.channel) {
        Object.assign(form, props.channel);
        file.value =  props.channel.avatar
    }
});

const handleFileUpload = (event) => {
    form.avatar = event.target.files[0];

    if (!form.avatar) {
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        file.value = e.target.result;
    };
    reader.readAsDataURL(form.avatar);
};

const loading = useLoadingBar()
const errors = ref({})
const uploadChannel = () => {
    console.log(form)
    loading.start()
  axios.post(route('channels.update', props.channel.id), form, {headers: {
          'Content-Type': 'multipart/form-data'
      }})
      .then(res => {
          loading.finish()
      })
      .catch(error => {
          loading.error()
          errors.value = {}
          if (error.response && error.response.data && error.response.data.errors) {
              errors.value = error.response.data.errors;
          }
      })
}

const languages = [
    {
        label: 'русский',
        value: 'russian',
    },
    {
        label: 'английский',
        value: 'english',
    },
];
const channelSubjects = [
    {
        label: 'gameing',
        value: 'gameing',
    },
    {
        label: 'tech',
        value: 'tech',
    },
];
const discountData = [
    {
        label: '10%',
        value: '10',
    },
    {
        label: '20%',
        value: '20',
    },
    {
        label: '30%',
        value: '30',
    },
    {
        label: '50%',
        value: '50',
    },
];
const handleUpdateChecked = (value) => {
    form.type = value;
};
const state = toRefs(form);
watch(state.type, (newRadio) => {
    if (newRadio === 'channel') {
        form.type = 'channel';
    } else if (newRadio === 'chat') {
        form.type = 'chat';
    }
});
</script>

<template>
    <AppLayout>
        <div class="max-w-2xl mx-auto text-center">
            <h1
                class="py-24 text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">
                Редактирование канала
            </h1>
            <div class="px-24 mb-16 flex gap-x-2.5 justify-center">
                <div v-show="file" class="avatar">
                    <img :src="file" alt="" />
                </div>
                <div class="flex flex-col items-center w-full">
                    <label
                        class="cursor-pointer w-full px-6 py-3.5 bg-purple-600 rounded-3xl text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal custom-file-upload">
                        <input
                            type="file"
                            class="hidden"
                            accept="image/*"
                            @change="handleFileUpload($event)" />
                        Загрузить фото канала/ чата
                    </label>
                    <p
                        class="text-start pt-5 text-violet-100 text-opacity-40 text-base font-normal font-['Inter'] leading-tight">
                        Формат изображения jpg, jpeg, png, не менее 140*140рх,не более
                        2800*2024рх
                    </p>
                </div>
            </div>
            <span class="text-red-500" v-if="errors.avatar">{{ errors.avatar[0] }}</span>
            <div class="flex flex-col gap-y-16">
                <div class="w-full text-start flex flex-col gap-y-3">
                    <h2
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        Название канала/чата
                    </h2>
                    <text-input
                        v-model="form.channel_name"
                        id="name"
                        type="text"
                        class="w-full py-3"
                        required
                        autofocus
                        autocomplete="title"
                        placeholder="Иванов Иван" />
                    <span class="text-red-500" v-if="errors.channel_name">{{ errors.channel_name[0] }}</span>
                </div>
                <div class="w-full text-start flex flex-col gap-y-3">
                    <h2
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        Описание канала/чата
                    </h2>
                    <text-area
                        v-model="form.description"
                        id="name"
                        type="textarea"
                        class="w-full py-3"
                        required
                        autofocus
                        autocomplete="description"
                        placeholder="Опишите особенности вашего канала, которые выделяют вас в каталоге. Не указывать личные контакты или ссылки на другие сайты." />
                    <span class="text-red-500" v-if="errors.description">{{ errors.description[0] }}</span>
                </div>
                <div class="w-full text-start flex flex-col gap-y-3">
                    <h2
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        Тематика канала/чата
                    </h2>
                    <n-select
                        placeholder="выберите тему канала/чата"
                        :theme-overrides="selectThemeOverrides"
                        v-model:value="form.topic"
                        :options="channelSubjects" />
                    <span class="text-red-500" v-if="errors.topic">{{ errors.topic[0] }}</span>
                </div>
                <div class="w-full text-start flex flex-col gap-y-3">
                    <h2
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        Ссылка на канал/чат
                    </h2>
                    <text-input
                        v-model="form.channel_url"
                        id="name"
                        type="text"
                        class="w-full py-3"
                        required
                        autofocus
                        autocomplete="channel"
                        placeholder="@channel или https://t.me/dr_amina_pirmanova" />
                    <span class="text-red-500" v-if="errors.channel_url">{{ errors.channel_url[0] }}</span>
                </div>
                <div class="w-full text-center justify-center flex flex-col gap-y-3">
                    <h2
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        Вы добавляете
                    </h2>
                    <div class="flex flex-col justify-start gap-y-2 items-start">
                        <n-checkbox
                            @click.prevent="form.type = 'channel'"
                            :checked="form.type === 'channel'"
                            :theme-overrides="checkboxToRadioThemeOverrides"
                            value="channel"
                            class="flex items-center justify-center"
                        ><p class="text-violet-100 text-sm font-normal font-['Inter']">
                            Канал
                        </p>
                        </n-checkbox>
                        <n-checkbox
                            @click.prevent="form.type = 'chat'"
                            :checked="form.type === 'chat'"
                            :theme-overrides="checkboxToRadioThemeOverrides"
                            value="chat"
                            class="flex items-center justify-center"
                        ><p class="text-violet-100 text-sm font-normal font-['Inter']">
                            Группу/ чат
                        </p>
                        </n-checkbox>
                        <span class="text-red-500" v-if="errors.type">{{ errors.type[0] }}</span>
                    </div>
                </div>
                <div class="w-full text-start flex flex-col gap-y-3">
                    <h2
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        Язык
                    </h2>
                    <n-select
                        :theme-overrides="selectThemeOverrides"
                        v-model:value="form.language"
                        :options="languages" />
                    <span class="text-red-500" v-if="errors.language">{{ errors.language[0] }}</span>
                </div>
            </div>
        </div>
        <div class="format mt-12">
            <div class="format__header">
                <div class="flex flex-col justify-start items-start gap-7">
                    <h3
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        Формат размещения
                    </h3>
                    <p
                        class="text-violet-100 text-opacity-60 text-base font-normal font-['Inter'] leading-tight">
                        Что такое форматы и как назначить им цену?<span class="underline"
                    >Читать инструкцию<br /></span
                    >Обращаем внимание, формат 1/24 — обязательный.
                    </p>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="border-b border-violet-100 border-opacity-40">
                    <div
                        class="grid justify-between items-center py-8"
                        style="grid-template-columns: 2fr 6fr 2fr">
                        <div class="flex items-center gap-x-2">
                            <n-checkbox
                                :theme-overrides="checkboxToRadioThemeOverrides"
                                v-model:checked="format_one_checkbox"
                                class="flex items-center justify-center" />
                            <h2 class="text-violet-100 text-2xl font-bold font-['Inter']">
                                1/24
                            </h2>
                        </div>
                        <div>
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">
                                Стоимость за 24 часа в ленте и 1 час в топе, минимум 400 руб.
                            </p>
                        </div>
                        <div class="flex items-center gap-x-2 justify-end">
                            <input
                                v-model="form.format_one"
                                @input="format_one_checkbox = form.format_one.trim() !== ''"
                                type="text"
                                class="w-24 text-violet-100 bg-transparent border-t-0 border-l-0 border-r-0 border-b focus:border-violet-700 border-violet-700 focus:ring-0 ring-0" />
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">
                                руб.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="border-b border-violet-100 border-opacity-40">
                    <div
                        class="grid justify-between items-center py-8"
                        style="grid-template-columns: 2fr 6fr 2fr">
                        <div class="flex items-center gap-x-2">
                            <n-checkbox
                                :theme-overrides="checkboxToRadioThemeOverrides"
                                v-model:checked="format_two_checkbox"
                                class="flex items-center justify-center" />
                            <h2 class="text-violet-100 text-2xl font-bold font-['Inter']">
                                2/48
                            </h2>
                        </div>
                        <div>
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">
                                Стоимость за 48 часов в ленте и 2 часа в топе
                            </p>
                        </div>
                        <div class="flex items-center gap-x-2 justify-end">
                            <input
                                v-model="form.format_two"
                                @input="format_two_checkbox = form.format_two.trim() !== ''"
                                type="text"
                                class="w-24 text-violet-100 bg-transparent border-t-0 border-l-0 border-r-0 border-b focus:border-violet-700 border-violet-700 focus:ring-0 ring-0" />
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">
                                руб.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="border-b border-violet-100 border-opacity-40">
                    <div
                        class="grid justify-between items-center py-8"
                        style="grid-template-columns: 2fr 6fr 2fr">
                        <div class="flex items-center gap-x-2">
                            <n-checkbox
                                :theme-overrides="checkboxToRadioThemeOverrides"
                                v-model:checked="format_three_checkbox"
                                class="flex items-center justify-center" />
                            <h2 class="text-violet-100 text-2xl font-bold font-['Inter']">
                                3/72
                            </h2>
                        </div>
                        <div>
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">
                                Стоимость за 72 часов в ленте и 3 часа в топе
                            </p>
                        </div>
                        <div class="flex items-center gap-x-2 justify-end">
                            <input
                                v-model="form.format_three"
                                @input="format_three_checkbox = form.format_three.trim() !== ''"
                                type="text"
                                class="w-24 text-violet-100 bg-transparent border-t-0 border-l-0 border-r-0 border-b focus:border-violet-700 border-violet-700 focus:ring-0 ring-0" />
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">
                                руб.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="border-b border-violet-100 border-opacity-40">
                    <div
                        class="grid justify-between items-center py-8"
                        style="grid-template-columns: 2fr 6fr 2fr">
                        <div class="flex items-center gap-x-2">
                            <n-checkbox
                                :theme-overrides="checkboxToRadioThemeOverrides"
                                v-model:checked="no_deletion_checkbox"
                                class="flex items-center justify-center" />
                            <h2 class="text-violet-100 text-2xl font-bold font-['Inter']">
                                3/без удаления
                            </h2>
                        </div>
                        <div>
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">
                                Стоимость за пост без удаления и 3 часа в топе
                            </p>
                        </div>
                        <div class="flex items-center gap-x-2 justify-end">
                            <input
                                v-model="form.no_deletion"
                                @input="no_deletion_checkbox = form.no_deletion.trim() !== ''"
                                type="text"
                                class="w-24 text-violet-100 bg-transparent border-t-0 border-l-0 border-r-0 border-b focus:border-violet-700 border-violet-700 focus:ring-0 ring-0" />
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">
                                руб.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-2xl mx-auto text-center mt-12">
            <div class="flex flex-col gap-y-16">
                <div>
                    <div class="w-full flex flex-col gap-y-3">
                        <h2
                            class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                            Источник подписчиков
                        </h2>
                        <text-area
                            v-model="form.subscribers_source"
                            id="name"
                            type="textarea"
                            class="w-full py-3"
                            required
                            autofocus
                            autocomplete="subscribers_source"
                            placeholder="Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации." />
                        <span class="text-red-500" v-if="errors.subscribers_source">{{ errors.subscribers_source[0] }}</span>
                    </div>
                </div>
                <div class="flex flex-col gap-y-8">
                    <div class="flex justify-center items-center gap-x-2.5">
                        <h2
                            class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                            Скидка на повторный заказ
                        </h2>
                        <n-switch
                            v-model:value="discount_check"
                            :theme-overrides="switchThemeOverrides" />
                    </div>
                    <p
                        class="text-violet-100 text-opacity-40 text-base font-normal font-['Inter'] leading-tight">
                        Если заказчик повторно купит у вас интеграцию, он получит скидку
                        автоматически
                    </p>
                    <n-select
                        :theme-overrides="selectThemeOverrides"
                        v-model:value="form.repeat_discount"
                        :options="discountData" />
                    <span class="text-red-500" v-if="errors.repeat_discount">{{ errors.repeat_discount[0] }}</span>
                </div>
                <div>
                    <div
                        class="flex justify-evenly text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        <button @click.prevent="uploadChannel" class="px-6 py-3.5 bg-purple-600 rounded-3xl">
                            Добавить канал / чат
                        </button>
                        <button @click.prevent="router.visit(route('channels'))" class="px-6 py-3.5 rounded-3xl border border-violet-700">
                            Отменить
                        </button>
                    </div>
                    <div class="mt-12">
                        <n-checkbox
                            :theme-overrides="checkboxThemeOverrides"
                            v-model:checked="form.terms"
                            class="flex items-center justify-center">
                            <div
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">
                                Ознакомлен с
                                <span class="underline">Правилами пользования Сервисом</span>
                            </div>
                        </n-checkbox>
                        <span class="text-red-500" v-if="errors.terms">{{ errors.terms[0] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">
.avatar {
    width: 130px;
    height: 130px;
    img {
        object-fit: fill;
        width: 130px;
        height: 130px;
        border-radius: 50%;
    }
}
</style>
