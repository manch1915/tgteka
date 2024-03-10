<script setup>
import { reactive } from "vue";
import {vMaska} from "maska";
import { checkboxThemeOverrides } from "@/themeOverrides.js";
import {Link} from "@inertiajs/vue3"
import { NCheckbox, useLoadingBar, useMessage } from "naive-ui";

const loading = useLoadingBar()
const message = useMessage()

const form = reactive({
    name: '',
    mobile_number: '',
    terms: false,
    errors: {
        name: '',
        mobile_number: '',
        terms: ''
    }
})

const handleSuccess = () => {
    message.success('Ваша заявка на обратный звонок принято')
    form.name = ''
    form.mobile_number = ''
    form.terms = false
}

const handleError = (e) => {
    loading.error()
    if (e.response) {
        if (e.response.data.errors){
            const errors = e.response.data.errors;
            Object.keys(errors).forEach(key => {
                form.errors[key] = errors[key][0];
            });
        }else if(e.response.data.message){
            message.error('Слишком много попыток повторите позже ')
        }
    }
}

const orderCallback = () => {
    loading.start()
    axios.post(route('order.callback'), form)
        .then(handleSuccess)
        .catch(e => handleError(e))
        .finally(() => {
            loading.finish()
        })
}
</script>

<template>
    <div class="sm:p-0 p-2">
        <div class="consultation__block sm:p-16 p-4 mt-14">
            <div class="form flex flex-col text-violet-100 justify-center gap-y-4">
                <div>
                    <input class="w-full" v-model="form.name" type="text" placeholder="Имя">
                    <div class="error-message">{{ form.errors.name }}</div>
                </div>
                <div>
                    <input class="w-full" v-model="form.mobile_number" v-maska data-maska="+7 (###) ###-##-##" type="text" placeholder="+7(___) - ___ - __ - __">
                    <div class="error-message">{{ form.errors.mobile_number }}</div>
                </div>
                <div>
                    <div class="form__checkbox flex items-center gap-x-2">
                        <n-checkbox v-model:checked="form.terms" :theme-overrides="checkboxThemeOverrides"/>
                        <label class="terms text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">
                            Нажимая на кнопку «Отправить» я соглашаюсь с
                            <Link :href="route('rules')" class="underline">
                                Правилами пользования Сервисом
                            </Link>
                        </label>
                    </div>
                    <div class="error-message">{{ form.errors.terms }}</div>
                </div>
                <button @click.prevent="orderCallback" class="cursor-pointer px-3 py-2 bg-purple-600 animation hover:bg-purple-800 rounded-full w-full gap-2.5 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                    Заказать обратный звонок
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.consultation__block{
    position: relative;
    border-radius: 0 40px 40px 40px;
    border: 1.5px solid #FFF;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
    backdrop-filter: blur(21px);
    .form{
        input[type=text]{
            padding: 13px 14px;
            border-radius: 30px;
            background: transparent;
            border: 1px solid #FFFFFF;
        }
    }
    &:after{
        content: '';
        position: absolute;
        right: -30%;
        bottom: -7%;
        background: url("/images/plane-consul.svg");
        width: 405px;
        height: 120px;

        @media screen and (max-width: 640px) {
            content: none;
        }
    }
}
.error-message {
    display: block;
    color: red;
    font-size: .8rem;
}
</style>
