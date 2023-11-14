<script setup>
const toUp = () => {
    window.scrollTo({top: 0, behavior: 'smooth'});
}
</script>

<template>
    <footer class="pb-4">
        <div class="container mx-auto mt-96">
            <div class="info flex flex-wrap justify-between py-12">
                <div class="flex flex-col justify-between gap-12">
                    <div class="logo flex items-center">
                        <img src="/images/logo.svg" alt="logo">
                        <div class="text-gray-200 text-3xl font-bold font-['Open Sans'] leading-10">logo</div>
                    </div>
                    <div class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                        © 2023 tgteka.ru
                    </div>
                    <ul class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                        <li class="py-1">Правила пользования</li>
                        <li class="py-1">Пользовательское соглашение</li>
                        <li class="py-1">Вакансии</li>
                    </ul>
                    <div class="sm:hidden block container mx-auto">
                        <div class="banks flex justify-end">
                            <div class="flex flex-wrap gap-2">
                                <img class="w-16" src="/images/yumoney.svg" alt="">
                                <img class="w-16" src="/images/visa.svg" alt="">
                                <img class="w-16" src="/images/master-cart.svg" alt="">
                                <img class="w-16" src="/images/mir.svg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="info__tgteka flex flex-col justify-between gap-12">
                    <div class="text-violet-100 sm:text-7xl text-xl font-bold font-['Open Sans'] leading-10">info@tgteka.ru</div>
                    <div class="flex sm:flex-row flex-col justify-between items-start">
                        <div class="icons flex justify-between gap-4">
                            <div class="icon flex flex-col items-center">
                                <img class="glow_icon" src="/images/telegram.svg" alt="telegram">
                                <div class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">
                                    Telegram
                                </div>
                            </div>
                            <div class="icon flex flex-col items-center">
                                <img class="glow_icon" src="/images/message.svg" alt="message">
                                <div class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">
                                    Почта
                                </div>
                            </div>
                        </div>
                        <div class="contact flex flex-col gap-2">
                            <div class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-loose">
                                Отдел продаж
                            </div>
                            <div class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-snug">
                                Пн-Пт: C 09:00 до 18:00
                            </div>
                            <div class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-snug">
                                +7 (495) 818-62-50
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <img src="/images/linesanime.svg" alt="lines">
        <div class="container mx-auto pt-12">
            <div class="flex justify-between ">
                <div class="flex flex-wrap gap-12">
                    <div class="list">
                        <h1 class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-loose">Продукты</h1>
                        <ul>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Каталог Telegram-каналов
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Каталог Telegram-ботов
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Каталог горящих предложений
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Аналитика Telegram-каналов
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Готовые предложения
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Бот
                                горящих предложений
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Бот
                                нотификаций
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Официальная платформа
                            </li>
                        </ul>
                    </div>
                    <div class="list">
                        <h1 class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-loose">Продукты</h1>
                        <ul>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Блог
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Наш
                                Telegram-канал
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Партнерская программа
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Владельцам каналов
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Отзывы о Название компании
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Бот
                                горящих предложений
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Бот
                                нотификаций
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Официальная платформа
                            </li>
                        </ul>
                    </div>
                    <div class="list">
                        <h1 class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-loose">Помощь</h1>
                        <ul>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                FAQ
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Бот
                                поддержки
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Напишите нам
                            </li>
                            <li class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Бот
                                нотификаций
                            </li>
                        </ul>
                    </div>
                </div>
                <div @click.prevent="toUp" class="up cursor-pointer sm:flex hidden flex-col items-center gap-1">
                    <img class="transition w-20" src="/images/arrow-circle-down.svg" alt="arrow">
                    <div class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Наверх</div>
                </div>
            </div>
        </div>
        <img class="py-6" src="/images/vector-line.svg" alt="lines">
        <div class="sm:block hidden container mx-auto">
            <div class="banks flex justify-end">
                <div class="flex flex-wrap gap-2">
                <img src="/images/yumoney.svg" alt="">
                <img src="/images/visa.svg" alt="">
                <img src="/images/master-cart.svg" alt="">
                <img src="/images/mir.svg" alt="">
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped lang="scss">
footer {
    background: url("/images/background-footer.svg") center no-repeat;
    overflow: auto;
    background-position-y: -10%;
    margin-top: -20px;
    background-size: cover;
}
.up{
    &:hover{
    img{
        -webkit-filter: drop-shadow(3px 3px 2px rgba(255, 255, 255, 1));
        filter: drop-shadow(0 0 2px rgba(255, 255, 255, 1));
    }
    }
}
.list{
    ul{
        li{
            cursor: pointer;
            transition: color 0.5s ease;
            &:hover{
                color: rgba(66, 38, 72, 1);
            }
        }
    }
}
</style>
