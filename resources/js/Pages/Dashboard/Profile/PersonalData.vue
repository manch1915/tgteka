<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import { reactive, ref} from "vue";
import TextInput from "@/Components/TextInput.vue";
import {router, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {useLoadingBar} from "naive-ui";

const props = defineProps({
    created_at: String,
})

const page = usePage()

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
            <div class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Профиль</div>
            <div class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">дата регистрации:
                {{ created_at }}
            </div>
            <div class="mt-12 flex flex-col gap-y-6">
                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Имя</p>
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="w-1/2"
                    required
                    autocomplete="username"
                    placeholder="Иванов Иван"
                />
                <span class="text-red-500" v-if="errors.name">{{ errors.name[0] }}</span>
                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Telegram-аккаунт</p>
                <TextInput
                    id="name"
                    v-model="form.telegram_username"
                    type="text"
                    class="w-1/2"
                    required
                    autocomplete="username"
                    placeholder="@channel или https://t.me/dr_amina_pirmanova"
                />
                <span class="text-red-500" v-if="errors.telegram_username">{{ errors.telegram_username[0] }}</span>
                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Телефон</p>
                <TextInput
                    id="name"
                    v-model="form.mobile_number"
                    type="text"
                    class="w-1/2"
                    required
                    autocomplete="username"
                    placeholder="+7 (___) ___-__-__"
                />
                <span class="text-red-500" v-if="errors.mobile_number">{{ errors.mobile_number[0] }}</span>
            </div>
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
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss">

</style>
