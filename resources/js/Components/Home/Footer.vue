<script setup>
const toUp = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
};
</script>

<template>
    <footer>
        <div class="pb-4 p-2">
            <div class="container mx-auto">
                <div class="info flex flex-wrap justify-between py-12 gap-3">
                    <div class="flex flex-col justify-between gap-12">
                        <div class="logo flex items-center w-28">
                            <img src="/images/logo.svg" alt="logo" />
                        </div>
                        <div
                            class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                        >
                            © 2023 tgteka.ru
                        </div>
                        <ul
                            class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                        >
                            <li class="py-1">Правила пользования</li>
                            <li class="py-1">Пользовательское соглашение</li>
                            <li class="py-1">Вакансии</li>
                        </ul>
                        <div class="sm:hidden block container mx-auto">
                            <div class="banks flex justify-end">
                                <div class="flex flex-wrap gap-2">
                                    <div class="w-16">
                                        <img src="/images/yumoney.svg" alt="" />
                                    </div>
                                    <div class="w-16">
                                        <img src="/images/visa.svg" alt="" />
                                    </div>
                                    <div class="w-16">
                                        <img
                                            src="/images/master-cart.svg"
                                            alt=""
                                        />
                                    </div>
                                    <div class="w-16">
                                        <img src="/images/mir.svg" alt="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="info__tgteka flex flex-col justify-between sm:gap-12 gap-6"
                    >
                        <div
                            class="text-violet-100 lg:text-7xl sm:text-5xl text-xl font-bold font-['Open Sans'] leading-10"
                        >
                            info@tgteka.ru
                        </div>
                        <div
                            class="flex sm:flex-row flex-col justify-between items-start gap-3"
                        >
                            <div class="icons flex justify-between gap-4">
                                <div class="icon flex flex-col items-center">
                                    <div class="glow_icon">
                                        <img
                                            src="/images/telegram.svg"
                                            alt="telegram"
                                        />
                                    </div>

                                    <div
                                        class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                                    >
                                        Telegram
                                    </div>
                                </div>
                                <div class="icon flex flex-col items-center">
                                    <div class="glow_icon">
                                        <img
                                            src="/images/message.svg"
                                            alt="message"
                                        />
                                    </div>

                                    <div
                                        class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                                    >
                                        Почта
                                    </div>
                                </div>
                            </div>
                            <div class="contact flex flex-col gap-3">
                                <div
                                    class="text-violet-100 text-2xl font-bold font-['Open Sans']"
                                >
                                    Отдел продаж
                                </div>
                                <div
                                    class="text-violet-100 text-lg font-normal font-['Open Sans']"
                                >
                                    Пн-Пт: C 09:00 до 18:00
                                </div>
                                <a
                                    href="tel:+74958186250"
                                    class="text-violet-100 text-lg font-bold font-['Open Sans']"
                                >
                                    +7 (495) 818-62-50
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full">
            <img src="/images/linesanime.svg" alt="lines" />
        </div>
        <div class="pb-4 p-2">
            <div class="container mx-auto pt-12">
                <div class="flex justify-between">
                    <div class="flex flex-wrap gap-x-12 gap-y-10">
                        <div class="list">
                            <h1
                                class="text-violet-100 text-2xl font-bold font-['Open Sans'] mb-1"
                            >
                                Продукты
                            </h1>
                            <ul>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Каталог Telegram-каналов
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Каталог Telegram-ботов
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Каталог горящих предложений
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Аналитика Telegram-каналов
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Готовые предложения
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Бот горящих предложений
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Бот нотификаций
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Официальная платформа
                                </li>
                            </ul>
                        </div>
                        <div class="list">
                            <h1
                                class="text-violet-100 text-2xl font-bold font-['Open Sans'] mb-1"
                            >
                                Сервисы
                            </h1>
                            <ul>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Блог
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Наш Telegram-канал
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Партнерская программа
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Владельцам каналов
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Отзывы о Название компании
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Бот горящих предложений
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Бот нотификаций
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Официальная платформа
                                </li>
                            </ul>
                        </div>
                        <div class="list">
                            <h1
                                class="text-violet-100 text-2xl font-bold font-['Open Sans'] mb-1"
                            >
                                Помощь
                            </h1>
                            <ul>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    FAQ
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Бот поддержки
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Напишите нам
                                </li>
                                <li
                                    class="py-1 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                                >
                                    Бот нотификаций
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div
                        @click.prevent="toUp"
                        class="up cursor-pointer sm:flex hidden flex-col items-center gap-1"
                    >
                        <img
                            class="transition w-20"
                            src="/images/arrow-circle-down.svg"
                            alt="arrow"
                        />
                        <div
                            class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                        >
                            Наверх
                        </div>
                    </div>
                </div>
            </div>
            <img class="py-6" src="/images/vector-line.svg" alt="lines" />
            <div class="sm:block hidden container mx-auto">
                <div class="banks flex justify-end">
                    <div class="flex flex-wrap gap-2">
                        <div class="w-16">
                            <img src="/images/yumoney.svg" alt="" />
                        </div>
                        <div class="w-16">
                            <img src="/images/visa.svg" alt="" />
                        </div>
                        <div class="w-16">
                            <img src="/images/master-cart.svg" alt="" />
                        </div>
                        <div class="w-16">
                            <img src="/images/mir.svg" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped lang="scss">
footer {
    overflow: auto;
    padding-top: 250px;
    position: relative;
    &:before {
        content: "";
        position: absolute;
        display: block;
        top: 0;
        left: 0;
        z-index: -1;
        background: url("/images/background-footer.svg") top no-repeat;
        background-size: cover;
        width: 100%;
        height: 100%;
        @media screen and (max-width: 640px) {
        }
    }
    @media screen and (min-width: 2448px) {
        padding-top: 300px;
    }
    @media screen and (max-width: 640px) {
        padding-top: 400px;
        margin-top: -200px;
    }
}
.up {
    &:hover {
        img {
            -webkit-filter: drop-shadow(3px 3px 2px rgba(255, 255, 255, 1));
            filter: drop-shadow(0 0 2px rgba(255, 255, 255, 1));
        }
    }
}
.list {
    ul {
        li {
            cursor: pointer;
            transition: color 0.5s ease;
            &:hover {
                color: rgb(210, 132, 229);
                text-shadow: 0 0 3px rgba(255, 255, 255, 0.62);
            }
        }
    }
}
.glow_icon {
    width: 40px;
    height: 40px;
}
</style>
