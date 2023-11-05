<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import { reactive, ref} from "vue";
import TextInput from "@/Components/TextInput.vue";
import {usePage} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

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

const submit = async () => {
    await axios.patch(route('personal-data.store'), form)
        .then(res => {
            console.log(res)
        })
        .catch(error => {
            if (error.response && error.response.status === 422) {
                errors.name = error.response.data.errors.name;
                errors.telegram_username = error.response.data.errors.telegram_username;
                errors.mobile_number = error.response.data.errors.mobile_number;
            } else {
                console.log(error)
            }
        })
};

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
                    autofocus
                    autocomplete="username"
                    placeholder="Иванов Иван"
                />
                <InputError :message="errors.name[0]"/>
                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Telegram-аккаунт</p>
                <TextInput
                    id="name"
                    v-model="form.telegram_username"
                    type="text"
                    class="w-1/2"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="@channel или https://t.me/dr_amina_pirmanova"
                />
                <InputError :message="errors.telegram_username[0]"/>
                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Телефон</p>
                <TextInput
                    id="name"
                    v-model="form.mobile_number"
                    type="text"
                    class="w-1/2"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="+7 (___) ___-__-__"
                />
                <InputError :message="errors.mobile_number[0]"/>
            </div>
            <div></div>
            <button
                @click.prevent="submit"
                class="mt-6 px-6 py-4 bg-purple-600 transition hover:bg-purple-900 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                Сохранить данные
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
