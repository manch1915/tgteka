<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { onBeforeMount, onMounted, onUnmounted, ref, watch } from "vue";
import { useHomeButtons } from "@/stores/homeButtons.js";
import { openLogin, openRegister } from "@/utilities/authModals.js";

const burgerActive = ref(false);
const toggleBurger = () => {
    burgerActive.value = !burgerActive.value;

    if (burgerActive.value) {
        document.body.style.overflowY = "hidden";
        document.querySelector("html").style.overflowY = "hidden";
    } else {
        document.body.style.overflowY = "";
        document.querySelector("html").style.overflowY = "";
    }
};
const width = ref(window.innerWidth);
const updateWidth = () => {
    width.value = window.innerWidth;
};

onMounted(() => {
    // Add the updateWidth function as a window resize listener
    window.addEventListener("resize", updateWidth);
});

onUnmounted(() => {
    // Remove listener when component is unmounted
    window.removeEventListener("resize", updateWidth);
});
const homeButtons = useHomeButtons();
const page = usePage();
const profileButtonTitle = ref(homeButtons.activeButton);

watch(
    () => homeButtons.activeButton,
    (newButton) => {
        profileButtonTitle.value = newButton;
    }
);
onBeforeMount(() => {
    homeButtons.setActiveButtonByUrl(page.url);
});

const clearBodyOverflow = () => {
    document.body.style.overflow = "";
    document.querySelector("html").style.overflowY = "";
};
</script>

<template>
    <div v-if="width >= 768" class="header">
        <div
            class="container mx-auto flex flex-wrap justify-between items-center py-2 px-6 nav-menu"
        >
            <div class="logo flex-1">
                <img
                    class="w-32"
                    src="/images/dashboard/logo.svg"
                    alt="Application logo"
                />
            </div>
            <nav class="navigation flex-1">
                <ul class="flex justify-center gap-x-4">
                    <Link :href="route('customers')">
                        <li
                            class="text-paleblue font-bold py-1 px-4 cursor-pointer"
                            :class="{ 'background': $page.url === '/' }"
                        >
                            Заказчикам
                        </li>
                    </Link>
                    <Link :href="route('owners')">
                        <li
                            class="text-paleblue font-bold py-1 px-4 cursor-pointer whitespace-nowrap"
                            :class="{ 'background': $page.url === '/owners' }"
                        >
                            Владельцу канала
                        </li>
                    </Link>
                    <!--                    <li class="text-paleblue select-none font-bold py-1 px-4 cursor-pointer flex gap-1">Сервисы <i class="arrow-circle-down"></i></li>-->
                </ul>
            </nav>
            <div class="flex flex-1 justify-end">
                <ul class="flex items-center gap-x-2 text-paleblue font-bold">
                    <li><img src="/images/house-user.svg" alt="home" /></li>
                    <li class="flex gap-x-1">
                        <span
                            class="hover:text-violet-300 cursor-pointer"
                            @click.prevent="openRegister()"
                            >Регистрация</span
                        >
                        /
                        <span
                            class="hover:text-violet-300 cursor-pointer"
                            @click.prevent="openLogin()"
                            >Войти</span
                        >
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div v-else class="header">
        <div
            class="container mx-auto flex flex-wrap justify-between items-center px-4 nav-menu"
        >
            <div class="flex gap-x-4 items-center z-[4]">
                <div @click.prevent="toggleBurger" class="burger-icon p-2">
                    <div id="nav-icon3" :class="{ open: burgerActive }">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div class="logo flex-1">
                    <img
                        class="w-16"
                        src="/images/dashboard/logo.svg"
                        alt="Application logo"
                    />
                </div>
            </div>
            <div @click.prevent="openRegister">
                <img class="w-10" src="/images/house-user.svg" alt="home" />
            </div>
        </div>
    </div>
    <Transition>
        <div
            v-show="burgerActive"
            :class="{ active: burgerActive }"
            class="header-nav px-4"
        >
            <div
                class="flex gap-x-3 pt-10 text-white text-sm font-bold font-['Open Sans'] leading-tight"
            >
                <Link as="button" :href="route('customers')" :onStart="clearBodyOverflow"
                    class="customers-btn w-full py-2"
                      :class="{ 'background': $page.url === '/' }"
                >
                    Заказчикам
                </Link>
                <Link as="button" :href="route('owners')" :onStart="clearBodyOverflow"
                    class="owners-btn w-full py-2"
                      :class="{ 'background': $page.url === '/owners' }"
                >
                    Владельцу канала
                </Link>
            </div>
            <div class="justify-start items-center gap-1 inline-flex mt-12">
                <div
                    class="text-violet-100 text-base font-bold font-['Open Sans'] leading-tight"
                >
                    Сервисы
                </div>
                <img
                    class="w-9"
                    src="/images/arrow-circle-down.svg"
                    alt=""
                    srcset=""
                />
            </div>
            <div class="flex mt-4">
                <div
                    class="nav-block rounded border p-5 border-violet-100 backdrop-blur-xl"
                >
                    <div
                        class="flex-col justify-start items-start gap-2.5 inline-flex"
                    >
                        <div
                            class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                        >
                            Каталог каналов
                        </div>
                        <div
                            class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                        >
                            Каталог ботов
                        </div>
                        <div
                            class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                        >
                            Готовые предложения
                        </div>
                        <div
                            class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                        >
                            Аналитика Telegram-каналов
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <button
                            @click.prevent="openLogin"
                            class="text-center px-6 py-4 mt-5 bg-deepblue rounded-full shadow-inner border border-white border-opacity-10 justify-center items-center gap-2.5 inline-flex text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                        >
                            Купить размещение
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-24 justify-start items-center gap-2 inline-flex">
                <div class="icon-phone">
                    <img src="/images/phone.svg" alt="phone" srcset="" />
                </div>

                <div
                    class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-normal"
                >
                    +7 (495) 818-62-50
                </div>
            </div>
            <div class="flex gap-x-4 mt-5 pb-24">
                <div class="flex flex-col justify-center items-center">
                    <div class="glow_icon">
                        <img class="select-none" src="/images/telegram.svg" alt="telegram" />
                    </div>

                    <div
                        class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                    >
                        Telegram
                    </div>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <div class="glow_icon">
                        <img class="select-none" src="/images/message.svg" alt="message" />
                    </div>
                    <div
                        class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                    >
                        Почта
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style lang="scss" scoped>
.header {
    border-bottom: 1px solid rgba(234, 224, 255, 0.3);
    position: relative;
    z-index: 111;
    height: 100px;
    display: flex;
    align-items: center;
    @media screen and (max-width: 768px) {
        height: 60px;
    }
}
.header-nav {
    width: 100vw;
    height: 100vh;
    top: 0;
    overflow-y: scroll;
    background: linear-gradient(
        210deg,
        rgba(20, 7, 50, 0.9) 30%,
        rgba(82, 70, 105, 0.9)
    );
    backdrop-filter: blur(7px);
    z-index: 3;
    transition: all 0.5s;
    button {
        line-height: 1.2;
    }
}
.v-enter-active,
.v-leave-active {
    transition: all 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
.nav-block {
    background: linear-gradient(
        20deg,
        rgba(255, 255, 255, 0.3),
        rgba(255, 255, 255, 0.1)
    );
}
.customers-btn {
    border-radius: 100px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: transparent;
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
}
.owners-btn {
    border-radius: 100px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
}
.arrow-circle-down {
    height: 24px;
    width: 24px;
    content: url("/images/arrow-circle-down.svg");
}

.navigation {
    ul {
        li {
            border-radius: 100px;
            border: 1px solid transparent;
            transition: all 0.5s;

            &:hover {
                border-radius: 100px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                background: #171961;
                box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
            }
        }
    }
}
.burger-icon {
    width: 40px;
    height: 40px;
    border-radius: 100px;
    border: 1px solid #565656;
    background: rgba(255, 255, 255, 0.2);
    cursor: pointer;
    #nav-icon3 {
        position: relative;
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
        -webkit-transition: 0.5s ease-in-out;
        -moz-transition: 0.5s ease-in-out;
        -o-transition: 0.5s ease-in-out;
        transition: 0.5s ease-in-out;
    }

    #nav-icon3 span {
        display: block;
        position: absolute;
        height: 3px;
        width: 100%;
        background: #eae0ff;
        border-radius: 9px;
        opacity: 1;
        left: 0;
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
        -webkit-transition: 0.25s ease-in-out;
        -moz-transition: 0.25s ease-in-out;
        -o-transition: 0.25s ease-in-out;
        transition: 0.25s ease-in-out;
    }
    /* Icon 3 */

    #nav-icon3 span:nth-child(1) {
        top: 0px;
    }

    #nav-icon3 span:nth-child(2),
    #nav-icon3 span:nth-child(3) {
        top: 9px;
    }

    #nav-icon3 span:nth-child(4) {
        top: 18px;
        width: 70%;
    }

    #nav-icon3.open span:nth-child(1) {
        top: 18px;
        width: 0%;
        left: 50%;
    }

    #nav-icon3.open span:nth-child(2) {
        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        -o-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    #nav-icon3.open span:nth-child(3) {
        -webkit-transform: rotate(-45deg);
        -moz-transform: rotate(-45deg);
        -o-transform: rotate(-45deg);
        transform: rotate(-45deg);
    }

    #nav-icon3.open span:nth-child(4) {
        top: 18px;
        width: 0%;
        left: 50%;
    }
}

.background {
    border-radius: 100px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: #171961;
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
}
.glow_icon {
    width: 40px;
    height: 40px;
}
</style>
