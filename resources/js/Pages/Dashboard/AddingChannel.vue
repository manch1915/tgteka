<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import {
    NCheckbox,
    NDrawer,
    NDrawerContent,
    NSelect,
    NSlider,
    NSwitch,
    useLoadingBar,
} from "naive-ui";
import {
    switchThemeOverrides,
    checkboxThemeOverrides,
    selectThemeOverrides,
    checkboxToRadioThemeOverrides,
    sliderGenderThemeOverrides,
} from "@/themeOverrides.js";
import { computed, nextTick, reactive, ref, toRefs, watch } from "vue";
import { router, Link, Head } from "@inertiajs/vue3";
import { useMainStore } from "@/stores/main.js";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import { mdiFaceMan, mdiFaceWoman } from "@mdi/js";
import { Title } from "chart.js";
import Rules from "@/Pages/Rules.vue";
import Agreement from "@/Pages/Agreement.vue";

const discount_check = ref(false);
const format_one_checkbox = ref(true);
const format_two_checkbox = ref(false);
const format_three_checkbox = ref(false);
const no_deletion_checkbox = ref(false);

const form = reactive({
    channel_name: "",
    description: "",
    topic_id: null,
    // type: 'channel',
    url: "",
    subscribers_source: "",
    repeat_discount: null,
    terms: false,
    format_one_price: 0,
    format_two_price: 0,
    format_three_price: 0,
    no_deletion_price: 0,
    male_percentage: 30,
});

const loading = useLoadingBar();
const errors = ref({});
let errorRefs = reactive({});

const activeRules = ref(false);

const uploadChannel = () => {
    loading.start();
    axios
        .post(route("adding-channel.store"), form, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((res) => {
            loading.finish();
            router.visit(route("channels"));
        })
        .catch((error) => {
            loading.error();
            errors.value = {};
            if (error.response && error.response.data && error.response.data.errors) {
                errors.value = error.response.data.errors;
            }

            nextTick(() => {
                const firstErrorElement = document.querySelector(".text-errorred");
                if (firstErrorElement) {
                    firstErrorElement.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                }
            });
        });
};

const malePercentage = computed(() => form.male_percentage);
const femalePercentage = computed(() => 100 - form.male_percentage);

const store = useMainStore();
store.fetchTopics();

const channelSubjects = computed(() =>
    store.topics.map((topic) => ({
        label: topic.title,
        value: topic.id,
    })),
);

const discountData = [
    {
        label: "10%",
        value: "10",
    },
    {
        label: "20%",
        value: "20",
    },
    {
        label: "30%",
        value: "30",
    },
    {
        label: "50%",
        value: "50",
    },
];
const handleUpdateChecked = (value) => {
    form.type = value;
};

watch(
    () => form.type,
    (newRadio) => {
        if (newRadio === "channel") {
            form.type = "channel";
        } else if (newRadio === "chat") {
            form.type = "chat";
        }
    },
);
</script>

<template>
    <Head>
        <title>Добавление канала / чата</title>
    </Head>
    <AppLayout>
        <div class="mx-auto max-w-2xl text-center">
            <h1
                class="py-24 text-violet-100 lg:text-4xl text-3xl font-bold font-['Open Sans'] leading-10">
                Добавление канала / чата
            </h1>
            <div class="flex flex-col gap-y-16 lg:px-0 px-4">
                <div class="flex w-full flex-col gap-y-3 text-start">
                    <h2
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-tight">
                        Название канала/чата
                    </h2>
                    <div>
                        <text-input
                            v-model="form.channel_name"
                            id="name"
                            type="text"
                            class="w-full py-3"
                            required
                            autocomplete="title"
                            placeholder="Название" />
                        <span
                            class="text-errorred"
                            v-if="errors.channel_name"
                        >{{ errors.channel_name[0] }}</span
                        >
                    </div>
                </div>
                <div class="flex w-full flex-col gap-y-3 text-start">
                    <h2
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-tight">
                        Описание канала/чата
                    </h2>
                    <div>
                        <text-area
                            v-model="form.description"
                            id="name"
                            type="textarea"
                            class="w-full py-3"
                            required
                            autocomplete="description"
                            placeholder="Опишите особенности вашего канала, которые выделяют вас в каталоге. Не указывать личные контакты или ссылки на другие сайты." />
                        <span
                            class="text-errorred"
                            v-if="errors.description"
                        >{{ errors.description[0] }}</span
                        >
                    </div>
                </div>
                <div class="flex w-full flex-col gap-y-3 text-start">
                    <h2
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-tight">
                        Тематика канала/чата
                    </h2>
                    <div>
                        <n-select
                            placeholder="выберите тему канала/чата"
                            :theme-overrides="selectThemeOverrides"
                            v-model:value="form.topic_id"
                            :options="channelSubjects" />
                        <span
                            class="text-errorred"
                            v-if="errors.topic_id"
                        >{{ errors.topic_id[0] }}</span
                        >
                    </div>
                </div>
                <div class="flex w-full flex-col gap-y-3 text-start">
                    <h2
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-tight">
                        Ссылка на канал/чат
                    </h2>
                    <div>
                        <text-input
                            v-model="form.url"
                            id="name"
                            type="text"
                            class="w-full py-3"
                            required
                            autocomplete="channel"
                            placeholder="@channel или https://t.me/dr_amina_pirmanova" />
                        <span
                            class="text-errorred"
                            v-if="errors.url"
                            ref="el => { errorRefs.url = el }"
                        >{{ errors.url[0] }}</span
                        >
                    </div>
                </div>
                <!--                <div class="flex w-full flex-col justify-center gap-y-3 text-center">
                            <h2
                                class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-tight">
                                Вы добавляете
                            </h2>
                            <div class="flex flex-col items-start justify-start gap-y-2">
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
                                <span class="text-errorred" v-if="errors.type">{{ errors.type[0] }}</span>
                            </div>
                        </div>-->
                <div class="w-full text-start">
                    <h2
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-tight">
                        Соотношение полов, %
                    </h2>
                    <n-slider
                        class="my-4"
                        v-model:value="form.male_percentage"
                        :tooltip="false"
                        :step="10"
                        :theme-overrides="sliderGenderThemeOverrides" />
                    <div class="flex justify-between items-center">
                        <div class="border rounded p-4 flex items-center gap-x-1.5">
                            <BaseIcon
                                class="text-[#3259d2]"
                                size="25"
                                :path="mdiFaceMan" />
                            <p
                                class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-tight">
                                {{ malePercentage }}%
                            </p>
                        </div>
                        <div class="border rounded p-4 flex items-center gap-x-1.5">
                            <BaseIcon
                                class="text-[#dc78d8]"
                                size="25"
                                :path="mdiFaceWoman" />
                            <p
                                class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-tight">
                                {{ femalePercentage }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-12 format lg:px-0 px-4">
            <div class="format__header">
                <div class="flex flex-col items-start justify-start gap-7">
                    <h3
                        class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-tight">
                        Формат размещения
                    </h3>
                    <p
                        class="text-violet-100 text-opacity-60 text-base font-normal font-['Inter'] leading-tight">
                        Что такое форматы и как назначить им цену?
                        <span class="underline">Читать инструкцию<br /></span>
                        Обращаем внимание, формат 1/24 — обязательный.
                    </p>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="border-b border-violet-100 border-opacity-40">
                    <div class="format__grid items-center justify-between py-8">
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
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-tight">
                                Стоимость за 24 часа в ленте и 1 час в топе, минимум 400 руб.
                            </p>
                        </div>
                        <div class="flex items-center justify-end gap-x-2">
                            <input
                                v-model="form.format_one_price"
                                @input="
                  format_one_checkbox = form.format_one_price.trim() !== ''
                "
                                type="text"
                                class="w-24 border-t-0 border-r-0 border-b border-l-0 border-violet-700 bg-transparent text-violet-100 ring-0 focus:border-violet-700 focus:ring-0" />
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-tight">
                                руб.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="border-b border-violet-100 border-opacity-40">
                    <div class="format__grid items-center justify-between py-8">
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
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-tight">
                                Стоимость за 48 часов в ленте и 2 часа в топе
                            </p>
                        </div>
                        <div class="flex items-center justify-end gap-x-2">
                            <input
                                v-model="form.format_two_price"
                                @input="
                  format_two_checkbox = form.format_two_price.trim() !== ''
                "
                                type="text"
                                class="w-24 border-t-0 border-r-0 border-b border-l-0 border-violet-700 bg-transparent text-violet-100 ring-0 focus:border-violet-700 focus:ring-0" />
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-tight">
                                руб.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="border-b border-violet-100 border-opacity-40">
                    <div class="format__grid items-center justify-between py-8">
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
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-tight">
                                Стоимость за 72 часов в ленте и 3 часа в топе
                            </p>
                        </div>
                        <div class="flex items-center justify-end gap-x-2">
                            <input
                                v-model="form.format_three_price"
                                @input="
                  format_three_checkbox = form.format_three_price.trim() !== ''
                "
                                type="text"
                                class="w-24 border-t-0 border-r-0 border-b border-l-0 border-violet-700 bg-transparent text-violet-100 ring-0 focus:border-violet-700 focus:ring-0" />
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-tight">
                                руб.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="border-b border-violet-100 border-opacity-40">
                    <div class="format__grid items-center justify-between py-8">
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
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-tight">
                                Стоимость за пост без удаления и 3 часа в топе
                            </p>
                        </div>
                        <div class="flex items-center justify-end gap-x-2">
                            <input
                                v-model="form.no_deletion_price"
                                @input="
                  no_deletion_checkbox = form.no_deletion_price.trim() !== ''
                "
                                type="text"
                                class="w-24 border-t-0 border-r-0 border-b border-l-0 border-violet-700 bg-transparent text-violet-100 ring-0 focus:border-violet-700 focus:ring-0" />
                            <p
                                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-tight">
                                руб.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mx-auto mt-12 max-w-2xl text-center lg:px-0 px-2">
            <div class="flex flex-col gap-y-16">
                <div>
                    <div class="flex w-full flex-col gap-y-3 text-start">
                        <h2
                            class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-tight">
                            Источник подписчиков
                        </h2>
                        <div>
                            <text-area
                                v-model="form.subscribers_source"
                                id="name"
                                type="textarea"
                                class="w-full py-3"
                                required
                                autocomplete="subscribers_source"
                                placeholder="Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации." />
                            <span
                                class="text-errorred"
                                v-if="errors.subscribers_source"
                            >{{ errors.subscribers_source[0] }}</span
                            >
                        </div>

                    </div>
                </div>
                <div class="flex flex-col gap-y-8">
                    <div class="flex items-center justify-center gap-x-2.5">
                        <h2
                            class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-tight">
                            Скидка на повторный заказ
                            <n-switch
                                v-model:value="discount_check"
                                :theme-overrides="switchThemeOverrides" />
                        </h2>

                    </div>
                    <p
                        class="text-violet-100 text-opacity-40 text-base font-normal font-['Inter'] leading-tight">
                        Если заказчик повторно купит у вас интеграцию, он получит скидку
                        автоматически
                    </p>
                    <div>
                        <n-select
                            :theme-overrides="selectThemeOverrides"
                            v-model:value="form.repeat_discount"
                            :options="discountData" />
                        <span
                            class="text-errorred"
                            v-if="errors.repeat_discount"
                        >{{ errors.repeat_discount[0] }}</span
                        >
                    </div>
                </div>
                <div>
                    <div>
                        <div class="flex items-center justify-center text-center gap-x-2">
                            <p class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-tight">
                                <n-checkbox
                                    :theme-overrides="checkboxThemeOverrides"
                                    v-model:checked="form.terms"/>
                                Ознакомлен с
                                <span class="cursor-pointer underline" @click.prevent="activeRules = !activeRules">
                                    Правилами пользования Сервисом
                                </span>
                            </p>
                        </div>

                        <span
                            class="text-errorred"
                            v-if="errors.terms"
                        >{{ errors.terms[0] }}</span
                        >
                    </div>
                    <div
                        class="flex lg:flex-row flex-col mt-12 gap-y-2 justify-evenly text-violet-100 text-lg font-bold font-['Open Sans'] leading-tight">
                        <button
                            @click.prevent="uploadChannel"
                            class="rounded-3xl btn_gradient-purple transition hover:bg-purple-800 px-6 py-3.5">
                            Добавить канал / чат
                        </button>
                        <button
                            @click.prevent="router.visit(route('channels'))"
                            class="rounded-3xl border border-violet-700 bg-transparent transition hover:bg-red-500 px-6 py-3.5">
                            Отменить
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <n-drawer
            v-model:show="activeRules"
            width="100%"
            height="90%"
            placement="bottom">
            <n-drawer-content
                closable
                title="Правила пользования">
                <Rules />
            </n-drawer-content>
        </n-drawer>
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
.format__grid {
    display: grid;
    justify-items: start;
    row-gap: 1rem;
    grid-template-columns: 2fr 6fr 2fr;
    @media screen and (max-width: 1024px) {
        grid-template-columns: 1fr;
    }
}
</style>
