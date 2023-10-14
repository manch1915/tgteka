<script setup>

</script>

<template>
    <div class="issues flex flex-col items-center justify-center mt-40 pb-20">
        <div class="text-center text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-violet-600 text-6xl font-bold font-['Open Sans'] ">
            Требуется консультация по работе сервиса?
        </div>
        <div class="issues__block p-16 mt-14">
            <!--TODO gradient border-->
            <div class="form flex flex-col justify-center gap-y-2">
                <input type="text" placeholder="Имя">
                <input type="text" placeholder="+7(___) - ___ - __ - __">
                <div class="form__checkbox">
                    <!--TODO checkbox styling-->
                    <input type="checkbox" name="terms" class="accent-gray-700 checked:accent-white bg-black text-gray-300 rounded cursor-pointer">
                    <label for="terms">
                        <span class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">
                            Нажимая на кнопку «Отправить» я соглашаюсь  <br/>с </span>
                        <span class="text-violet-100 text-sm font-normal font-['Open Sans'] underline leading-tight">
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
