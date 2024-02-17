<script setup>
import {closeModal} from "jenesius-vue-modal";
import {NInput, useMessage} from "naive-ui";
import {inputThemeOverrides} from "@/themeOverrides.js";
import {ref} from "vue";

const password = ref('')
const message = useMessage()
const error = ref('')

const twoFactorAuthentication = () => {
    axios.post(route('two-factor.enable'), {password: password.value})
        .then(r => {
            message.success('Двухэтапная аутентификация успешно включён')
            closeModal()
        })
        .catch(e => {
            if (e.response.data.message){
                error.value = e.response.data.message
            }
        })
}
</script>

<template>
    <div class="wrapper container">
        <div class="h-full flex flex-col">
            <div class="top header">
                <div class="flex items-center w-full">
                    <div class="flex-1"></div>
                    <div class="flex flex-1 flex-col justify-center sm:items-center items-start gap-y-6">
                        <h1 class="sm:text-center text-start text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10">Включить двухэтапную аутентификацию</h1>
                    </div>
                    <div class="flex-1 flex justify-end cursor-pointer" @click.prevent="closeModal()">
                        <img class="messenger_close" src="/images/Icon-close.svg" alt="">
                    </div>
                </div>
            </div>
            <div class="content flex flex-col justify-center w-1/3 m-auto">
                <n-input :theme-overrides="inputThemeOverrides" v-model:value="password" placeholder="Введите ваш пароль"/>
                <span v-if="error" class="block text-red-600">{{error}}</span>
                <button
                    @click.prevent="twoFactorAuthentication"
                    class="my-6 w-full px-4 py-2 bg-green-600 transition hover:bg-green-900 rounded-full text-violet-100 text-sm font-bold font-['Open Sans'] leading-normal">
                    Включить двухэтапную аутентификацию
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wrapper{
    height: auto;
    border: 1px solid #6522D9;
    background: #070C29;
    border-radius: 0 20px 20px 20px;

}
.top{
    padding: 20px 65px;
    border-radius: 30px 30px 0 0;
    background: rgba(7, 12, 41, 1);
    box-sizing: border-box;
    @media screen and (max-width: 640px){
        padding: 10px;
    }
}


.messenger_close{
    transition: all .5s;
    &:hover{
        filter: drop-shadow(0 0 8px #9d9d9d) ;
    }
}
</style>
