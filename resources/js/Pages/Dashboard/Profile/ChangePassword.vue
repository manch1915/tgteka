<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import { inputThemeOverrides} from "@/themeOverrides.js";
import {NInput, useLoadingBar} from "naive-ui";
import {reactive} from "vue";
import axios from "axios";

const form = reactive({
    password: '',
    password_confirmation: ''
})
const errors = reactive({
    password: [],
    password_confirmation: [],
});
const loading = useLoadingBar()

const submit = async () => {
    loading.start()
    await axios.patch(route('change-password.update'), form)
        .then((res) => {
            loading.finish()
            console.log(res)
            errors.password = [];
            errors.password_confirmation = [];
        })
        .catch(error => {
            if (error.response && error.response.data && error.response.data.errors) {
                errors.password = error.response.data.errors.password;
                errors.password_confirmation = error.response.data.errors.password_confirmation;
            }
            loading.error()
        })
};
</script>

<template>
    <AppLayout>
        <ProfileLayout>
            <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Изменение пароля</h1>
            <div class="mt-8 mb-6">
                <p class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Новый пароль</p>
            </div>
            <div class="flex w-3/4 flex-col gap-y-2">
                <n-input class="py-1.5 my-1 !w-2/4" v-model:value="form.password" placeholder="Новый пароль" type="password" show-password-on="click" :theme-overrides="inputThemeOverrides"/>
                <span class="text-red-500" v-if="errors.password">{{ errors.password[0] }}</span>
                <n-input class="py-1.5 my-1 !w-2/4" v-model:value="form.password_confirmation" placeholder="Подтвердите пароль" type="password" show-password-on="click" :theme-overrides="inputThemeOverrides"/>
                <span class="text-red-500" v-if="errors.password_confirmation">{{ errors.password_confirmation[0] }}</span>
                <button @click.prevent="submit" class="w-2/4 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal bg-purple-600 rounded-full py-4">Обновить пароль</button>
            </div>
            <div class="mt-8">
                <h2 class="my-2 text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Также вы можете сгенерировать новый пароль</h2>
                <p class="my-2 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Пароль будет выслан на указанную вами почту<br/>e****@admin.com</p>
                <button class="my-2 border py-2 px-4 border-violet-700 rounded-3xl text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Сгенерировать</button>
            </div>
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss">

</style>
