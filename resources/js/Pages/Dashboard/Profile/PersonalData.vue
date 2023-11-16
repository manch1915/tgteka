<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import {computed, reactive, ref} from "vue";
import TextInput from "@/Components/TextInput.vue";
import {router, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {NInput, NSelect, useLoadingBar} from "naive-ui";
import {inputThemeOverrides, selectThemeOverrides} from "@/themeOverrides.js";

const props = defineProps({
    created_at: String,
})

const page = usePage()

const user = computed(() => page.props.auth.user)

const form = reactive({
    name: ref(page.props.auth.user.name),
    telegram_username: ref(page.props.auth.user.telegram_username),
    mobile_number: ref(page.props.auth.user.mobile_number),
});

const errors = reactive({
    name: ref([]),
    telegram_username: ref([]),
    mobile_number: ref([])
});
const loading = useLoadingBar()

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

const submit = async () => {
    loading.start()
    await axios.patch(route('personal-data.store'), form)
        .then(() => {
            loading.finish()

            errors.name = [];
            errors.telegram_username = [];
            errors.mobile_number = [];
        })
        .catch(error => {
            if (error.response && error.response.data && error.response.data.errors) {
                errors.name = error.response.data.errors.name;
                errors.telegram_username = error.response.data.errors.telegram_username;
                errors.mobile_number = error.response.data.errors.mobile_number;
            }
            loading.error()
        })
};
const logout = () => {
    loading.start()
    axios.post(route('logout'))
        .then(res => {
            loading.finish()
            router.visit(route('customers'))
        })
}

</script>

<template>
    <AppLayout>
        <ProfileLayout>
            <div class="text-center sm:text-left">
                <p class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Личные данные</p>
            </div>
            <div class="sm:px-0 px-4 pt-4 text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">дата регистрации:
                {{ created_at }}
            </div>
            <div class="mt-12 flex flex-col gap-y-6 px-4 sm:px-0">
                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Имя</p>
                <n-input v-model:value="form.name" class="py-1.5 my-1 sm:!w-1/2" placeholder="Иванов Иван" :theme-overrides="inputThemeOverrides"/>
                <span class="text-red-500" v-if="errors.name">{{ errors.name[0] }}</span>
                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Telegram-аккаунт</p>
                <n-input v-model:value="form.telegram_username" class="py-1.5 my-1 sm:!w-1/2" placeholder="@channel или https://t.me/dr_amina_pirmanova" :theme-overrides="inputThemeOverrides"/>
                <span class="text-red-500" v-if="errors.telegram_username">{{ errors.telegram_username[0] }}</span>
                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Телефон</p>
                <n-input v-model:value="form.mobile_number" class="py-1.5 my-1 sm:!w-1/2" placeholder="+7 (___) ___-__-__" :theme-overrides="inputThemeOverrides"/>
                <span class="text-red-500" v-if="errors.mobile_number">{{ errors.mobile_number[0] }}</span>
                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Язык уведомлений</p>
                <n-select class="sm:w-1/2" :theme-overrides="selectThemeOverrides" :options="languages" default-value="russian"/>
                <span class="text-red-500" v-if="errors.mobile_number">{{ errors.mobile_number[0] }}</span>
            </div>
            <div class="px-4 sm:px-0">
            <button
                @click.prevent="submit"
                class="mt-6 px-6 py-4 bg-purple-600 transition hover:bg-purple-900 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                Сохранить данные
            </button>
            <br>
            <button
                @click.prevent="logout"
                class="mt-6 px-6 py-4 bg-transparent transition hover:bg-red-800 border border-red-900 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                Выйти
            </button>
            <br/>
            <button class="mt-12 text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">
                Удалить профиль
            </button>
            </div>
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss">

</style>
