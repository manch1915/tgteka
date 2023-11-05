<script setup>

</script>

<template>
    <div class="issues flex flex-col items-center justify-center mt-40 sm:pb-20 md:p-0 p-4 overflow-hidden">
        <div class="text-center py-6 text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-violet-600 sm:text-6xl text-3xl font-bold font-['Open Sans'] ">
            Возникли технические вопросы: <br>
            проблемы с доступом или оплатой?
        </div>
        <div class="text-center text-violet-100 text-base font-bold font-['Open Sans'] leading-tight">Заполните форму и мы с вами свяжемся</div>
        <div class="issues__block sm:p-16 p-6 mt-14">
            <!--TODO gradient border-->
            <div class="form flex flex-col justify-center gap-y-2">
                <input type="text" placeholder="Имя">
                <input type="text" placeholder="+7(___) - ___ - __ - __">
                <div class="form__checkbox flex items-center gap-x-1.5">
                    <!--TODO checkbox styling-->
                    <input type="checkbox" name="terms" class="accent-gray-700 checked:accent-white bg-black text-gray-300 rounded cursor-pointer">
                    <label class="terms text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">
                        Нажимая на кнопку «Отправить» я соглашаюсь с
                        <span class="underline">
                            Правилами пользования Сервисом
                        </span>
                    </label>
                </div>
                <button class="px-3 py-2 bg-purple-600 rounded-full w-full gap-2.5 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                    Заказать обратный звонок
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.issues {
    background: url("/images/issues.svg") no-repeat center;
    background-position-y: 0;
    background-size: cover;
    padding-top: 400px;
    @media screen and (max-width: 640px) {
        padding-top: 100px;

    }
}
.issues__block{
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
