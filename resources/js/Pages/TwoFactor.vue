<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {closeModal} from "jenesius-vue-modal";
import { reactive,ref } from "vue";
import {inputThemeOverrides} from "@/themeOverrides.js";
import {NInput, useLoadingBar} from "naive-ui";
import {router, Head} from "@inertiajs/vue3";

closeModal()

const props = defineProps({
    token: String,
    ziggy: Object
})

const loading = useLoadingBar()

const form = reactive({
    two_factor_code: ''
})
const error = ref('')
const verify = () => {
    loading.start()
    axios.post(route('two-factor.verify'), form)
        .then(response => {
            loading.finish()
            console.log(response)

            if (response.data.message === 'Verification successful') {
                router.visit(response.data.redirect_to);
            }
        })
        .catch(caughtError => {
            loading.error()
            if (caughtError.response.data.message) {
                error.value = caughtError.response.data.message;
            }
        });
}
</script>
<template>
    <Head>
        <title>Двухэтапная аутентификация</title>
    </Head>

    <MainLayout>
        <div class="container mx-auto flex flex-col sm:w-1/3 gap-y-10 sm:p-0 p-2">
            <div class="text-center mt-5 text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Двухэтапная аутентификация</div>
            <div>
                <n-input v-model:value="form.two_factor_code" type="text" class="py-1.5 my-1 sm:!w-full" placeholder="Код" :theme-overrides="inputThemeOverrides"/>
                <span v-if="error" class="block my-1 text-red-500">{{error}}</span>
                <button @click.prevent="verify" class="w-full px-6 py-2 my-2 bg-purple-600 transition hover:bg-purple-800 rounded-full text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Подтвердить</button>
            </div>
        </div>
    </MainLayout>
</template>
<style scoped lang="scss">
</style>
