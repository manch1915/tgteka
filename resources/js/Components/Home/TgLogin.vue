<template>
    <a href="#" @click.prevent="login">
        <img
            class="hover:cursor-pointer"
            src="/images/loginTelegram.svg"
            alt=""
        /></a>
</template>

<script setup>
import { useMessage } from "naive-ui";
import {router} from "@inertiajs/vue3";

const message = useMessage()

const login = () =>{
    window.Telegram.Login.auth(
        { bot_id: '6568179891', request_access: true },
        (data) => {
            if (!data) {
                message.error('Произошла ошибка. Пожалуйста, попробуйте еще раз позже.')
                return
            }

            axios.post(route('telegram.callback'), data)
                .then(() => {
                    router.visit(route('catalog.channels.index'))
                })
                .catch(() => message.error('Произошла ошибка. Пожалуйста, попробуйте еще раз позже.'))
        }
    )
}

</script>
