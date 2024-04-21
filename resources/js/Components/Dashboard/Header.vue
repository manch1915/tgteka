<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { closeModal, pushModal } from "jenesius-vue-modal";
import { onMounted, reactive, ref, watch, watchEffect } from "vue";
import { useMainStore } from "@/stores/main.js";
import AnimatedNumber from "@/Components/Dashboard/AnimatedNumber.vue";
import Messenger from "@/Components/Messenger/Messenger.vue";
import { loadCart } from "@/channelHelpers.js";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import { mdiCart, mdiFaceAgent } from "@mdi/js";
import { useCartStore } from "@/stores/CartStore.js";
import { NBadge, useMessage } from "naive-ui";
import {connectWebSocket} from "@/utilities/webSocketService.js";

closeModal();

const store = useMainStore();
const cartStore = useCartStore();
const page = usePage();
const showMessage = useMessage();

const cart = reactive({ items: [] });
const unreadNotifications = ref(0);

watch(
    () => cartStore.cartUpdate,
    () => {
        cart.items = loadCart();
    }
);

onMounted(async () => {
    cart.items = loadCart();
    const response = await axios.get(route("get.unread-notifications-count"));
    unreadNotifications.value = response.data;
    connectWebSocket(page.props.auth.user.id, showMessage);
});

const isActiveRoute = (routeUrl) => page.url === routeUrl;

watchEffect(() => {
    if (
        page.props.auth.user &&
        page.props.auth.user.balance !== store.userBalance
    ) {
        store.setUserBalance(page.props.auth.user.balance);
    }
});

const burgerActive = ref(false);

const toggleBurger = () => {
    burgerActive.value = !burgerActive.value;

    if (burgerActive.value) {
        document.body.style.overflow = "hidden";
    } else {
        document.body.style.overflow = "";
    }
};

const openMessenger = () => {
    pushModal(Messenger);
};
</script>

<template>
    <header>
        <div
            class="container mx-auto sm:py-6 sm:border-none border-b-[1px] py-3"
        >
            <div class="flex justify-between items-center sm:p-0 px-2">
                <div class="flex sm:hidden gap-x-6">
                    <div @click.prevent="toggleBurger" class="burger-icon p-2">
                        <div id="nav-icon3" :class="{ open: burgerActive }">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="flex sm:hidden logo items-center">
                        <img class="w-16" src="/images/logo.svg" alt="logo" />
                    </div>
                </div>
                <div class="logo">
                    <div
                        class="hidden sm:inline-flex justify-start items-start gap-2.5"
                    >
                        <Link :href="route('catalog.channels.index')">
                            <img
                                class="w-16 md:w-24 lg:w-36"
                                src="/images/dashboard/logo.svg"
                                alt="logo"
                            />
                        </Link>
                    </div>
                </div>
                <nav class="flex sm:gap-x-3 gap-x-0">
                    <div class="balance-elements px-6 py-1">
                        <div
                            class="balance flex items-center sm:gap-x-3 gap-x-0"
                        >
                            <div
                                class="sm:border-r-[1px] pr-2 flex items-center"
                            >
                                <p
                                    class="sm:block hidden select-none text-violet-100 text-xs lg:text-lg font-bold font-['Open Sans'] leading-normal"
                                >
                                    Общий баланс&nbsp;
                                </p>
                                <p
                                    class="text-violet-100 select-none sm:text-xl md:text-xs lg:text-lg text-lg font-bold font-['Open Sans'] leading-normal"
                                >
                                    <animated-number
                                        :number="parseFloat(store.userBalance)"
                                    />&nbsp;₽
                                </p>
                            </div>
                            <Link
                                :href="route('replenishment')"
                                class="cursor-pointer replenish pr-2 flex items-center gap-x-1"
                            >
                                <p
                                    class="sm:block hidden select-none text-violet-100 text-xs lg:text-lg font-bold font-['Open Sans'] leading-normal"
                                >
                                    Пополнить
                                </p>
                                <div class="w-4 sm:w-6 lg:w-6">
                                    <img
                                        class="w-6 select-none"
                                        src="/images/group.svg"
                                        alt=""
                                    />
                                </div>
                            </Link>
                        </div>
                    </div>
                    <div class="hidden sm:flex interactive items-center">
                        <div
                            @click.prevent="openMessenger"
                            class="h-8 border-r-[1px] px-2 md:px-4 lg:px-5 flex flex-col justify-center"
                        >
                            <img class="select-none" src="/images/messenger.svg" alt="" />
                        </div>
                        <div
                            class="border-r-[1px] px-2 md:px-4 lg:px-5 h-8 flex flex-col justify-center"
                        >
                            <NBadge
                                color="#6522d9"
                                :value="unreadNotifications"
                                :max="15"
                            >
                                <Link :href="route('notifications')">
                                    <img
                                        class="select-none"
                                        src="/images/notification.svg"
                                        alt=""
                                    />
                                </Link>
                            </NBadge>
                        </div>
                        <div
                            v-if="cart.items.length > 0"
                            class="flex flex-col justify-center h-8 border-r-[1px] px-2 md:px-4 lg:px-5"
                        >
                            <Link
                                :href="route('cart')"
                                class="flex flex-col items-center"
                            >
                                <n-badge type="info" :value="cart.items.length">
                                    <BaseIcon
                                        class="text-purple-400 select-none"
                                        size="30"
                                        :path="mdiCart"
                                    />
                                </n-badge>
                            </Link>
                        </div>
                        <div
                            class="px-2 md:px-4 lg:px-5 h-8 border-r-[1px] flex flex-col justify-center"
                        >
                            <Link
                                :href="route('support')"
                                class="flex flex-col items-center"
                            >
                                <BaseIcon
                                    class="text-purple-400 select-none"
                                    size="30"
                                    :path="mdiFaceAgent"
                                />
                            </Link>
                        </div>
                        <div
                            class="px-2 md:px-4 lg:px-5 h-8 flex flex-col justify-center"
                        >
                            <Link :href="route('personal-data')">
                                <img class="select-none" src="/images/person.svg" alt="" />
                            </Link>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <Transition>
        <div
            v-show="burgerActive"
            :class="{ active: burgerActive }"
            class="header-nav px-2"
            style="height: 100vh"
        >
            <div
                class="flex gap-x-3 pt-8 text-white text-sm font-bold font-['Open Sans'] leading-tight"
            >
                <button
                    @click.prevent="
                        router.visit(route('catalog.channels.index'))
                    "
                    class="customers-btn w-full py-2 border rounded"
                >
                    Каталог
                </button>
                <button
                    @click.prevent="router.visit(route('placements'))"
                    class="owners-btn w-full py-2 border rounded"
                >
                    Мои размещения
                </button>
            </div>
            <div class="justify-start items-center gap-1 inline-flex mt-12">
                <div
                    class="text-violet-100 text-base font-bold font-['Open Sans'] leading-tight"
                >
                    Сервисы
                </div>
            </div>
            <div class="flex mt-4">
                <div
                    class="nav-block rounded border p-8 border-violet-100 backdrop-blur-xl"
                >
                    <div
                        class="flex-col justify-start items-start gap-2.5 inline-flex"
                    >
                        <Link :href="route('patterns')"
                            ><p
                                :class="{
                                    active: isActiveRoute('/patterns'),
                                    'text-violet-100 text-lg font-bold font-OpenSans leading-normal cursor-pointer': true,
                                }"
                            >
                                Мои шаблоны
                            </p></Link
                        >
                        <Link :href="route('placements')"
                            ><p
                                :class="{
                                    active: isActiveRoute('/placements'),
                                    'text-violet-100 text-lg font-bold font-OpenSans leading-normal cursor-pointer': true,
                                }"
                            >
                                Мои размещения
                            </p></Link
                        >
                        <Link :href="route('catalog.channels.index')"
                            ><p
                                :class="{
                                    active: isActiveRoute('/catalog/channels'),
                                    'text-violet-100 text-lg font-bold font-OpenSans leading-normal cursor-pointer': true,
                                }"
                            >
                                Каталог каналов
                            </p></Link
                        >
                        <Link :href="route('channels')"
                            ><p
                                :class="{
                                    active: isActiveRoute('/channels'),
                                    'text-violet-100 text-lg font-bold font-OpenSans leading-normal cursor-pointer': true,
                                }"
                            >
                                Мои каналы
                            </p></Link
                        >
                        <Link :href="route('order.index')"
                            ><p
                                :class="{
                                    active: isActiveRoute('/orders'),
                                    'text-violet-100 text-lg font-bold font-OpenSans leading-normal cursor-pointer': true,
                                }"
                            >
                                Заявки на размещение
                            </p></Link
                        >
                    </div>
                </div>
            </div>
            <div class="mt-24 justify-start items-center gap-2 inline-flex">
                <img src="/images/phone.svg" alt="phone" srcset="" />
                <div
                    class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-normal"
                >
                    +7 (495) 818-62-50
                </div>
            </div>
            <div class="flex gap-x-4 mt-5">
                <div class="flex flex-col justify-center items-center">
                    <div class="glow_icon">
                        <img src="/images/telegram.svg" alt="telegram" />
                    </div>
                    <div
                        class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                    >
                        Telegram
                    </div>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <div class="glow_icon">
                        <img src="/images/message.svg" alt="message" />
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
    <div class="nav hidden sm:block">
        <div class="container mx-auto">
            <div class="py-10">
                <ul class="flex gap-x-5 justify-center">
                    <Link :href="route('patterns')"
                        ><li
                            :class="{
                                active: isActiveRoute('/patterns'),
                                'text-violet-100 text-xs lg:text-lg font-bold font-OpenSans select-none leading-normal cursor-pointer': true,
                            }"
                        >
                            Мои шаблоны
                        </li></Link
                    >
                    <Link :href="route('placements')"
                        ><li
                            :class="{
                                active: isActiveRoute('/placements'),
                                'text-violet-100 text-xs lg:text-lg font-bold font-OpenSans select-none leading-normal cursor-pointer': true,
                            }"
                        >
                            Мои размещения
                        </li></Link
                    >
                    <Link :href="route('catalog.channels.index')"
                        ><li
                            :class="{
                                active: isActiveRoute('/catalog/channels'),
                                'text-violet-100 text-xs lg:text-lg font-bold font-OpenSans select-none leading-normal cursor-pointer': true,
                            }"
                        >
                            Каталог каналов
                        </li></Link
                    >
                    <Link :href="route('channels')"
                        ><li
                            :class="{
                                active: isActiveRoute('/channels'),
                                'text-violet-100 text-xs lg:text-lg font-bold font-OpenSans select-none leading-normal cursor-pointer': true,
                            }"
                        >
                            Мои каналы
                        </li></Link
                    >
                    <Link :href="route('order.index')"
                        ><li
                            :class="{
                                active: isActiveRoute('/orders'),
                                'text-violet-100 text-xs lg:text-lg font-bold font-OpenSans select-none leading-normal cursor-pointer': true,
                            }"
                        >
                            Заявки на размещение
                        </li></Link
                    >
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
header {
    background: #1c1d6d;
    @media screen and (max-width: 640px) {
        background: #070c29;
    }
    .balance-elements {
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: #171961;
        box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25) inset;
        @media screen and (max-width: 640px) {
            border-radius: 0;
            border: none;
            background: transparent;
            box-shadow: none;
        }
    }
}
.nav {
    background: #0d143a;
    li:hover {
        filter: drop-shadow(0 0 5px rgb(237 233 254));
    }
    li.active {
        filter: drop-shadow(0 0 5px rgb(237 233 254));
    }
}
p.active {
    filter: drop-shadow(0 0 5px rgb(237 233 254));
}
.interactive {
    div {
        img {
            cursor: pointer;
            transition: all 0.5s;
            &:hover {
                filter: drop-shadow(0 0 5px #8729ff);
                -webkit-filter: drop-shadow(0 0 5px #8729ff);
            }
        }
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

.replenish:hover {
    p {
        transition: filter 0.4s;
        filter: drop-shadow(0 0 5px rgb(237, 233, 254));
    }
    img {
        transition: filter 0.4s;
        filter: drop-shadow(0 0 5px rgb(237, 233, 254));
    }
}
</style>
