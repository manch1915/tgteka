<script setup>
import {NCheckbox} from "naive-ui";
import {checkboxThemeOverrides} from "@/themeOverrides.js";
</script>

<template>
    <div class="consultation flex flex-col items-center justify-center mt-40 pb-16">
        <div class="text-center text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-violet-600 sm:text-6xl text-3xl font-bold font-['Open Sans'] ">
            Требуется консультация <br/>по работе сервиса?
        </div>
        <div class="sm:p-0 p-2">
            <div class="consultation__block p-16 mt-14">
                <!--TODO gradient border-->
                <div class="form flex flex-col text-violet-100 justify-center gap-y-4">
                    <input type="text" placeholder="Имя">
                    <input type="text" placeholder="+7(___) - ___ - __ - __">
                    <div class="form__checkbox flex items-center gap-x-2">
                        <!--TODO checkbox styling-->
                        <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                        <label for="terms"><span
                            class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Нажимая на кнопку «Отправить» я соглашаюсь <br/>с </span><span
                            class="text-violet-100 text-sm font-normal font-['Open Sans'] underline leading-tight">Правилами пользования Сервисом</span></label>
                    </div>
                    <button class="px-3 py-2 bg-purple-600 transition hover:bg-purple-800 rounded-full w-full gap-2.5 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        Заказать обратный звонок
                    </button>
                </div>
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
    }
}
</style>
