<script setup>
import {Link, router} from '@inertiajs/vue3';
import {useModalStore} from '@/stores/authModal.js'
import {openModal} from "jenesius-vue-modal";
import Login from "@/Components/Auth/Login.vue";
import Register from "@/Components/Auth/Register.vue";
import {ref, watch} from "vue";
import PasswordRecovery from "@/Components/Auth/PasswordRecovery.vue";

const modals = {
    register: Register,
    login: Login,
    recovery: PasswordRecovery
};

const modalStore = useModalStore()
const closeModal = () => {
    modalStore.closeCurrentModal(); // Close the current modal
}

const openModalWithName = (value) => {
    closeModal();
    modalStore.setModalToOpen(value);
}

watch(() => modalStore.modalShouldOpen, (newModalName) => {
    if (newModalName) {
        closeModal() // Close any opened modal
        openModal(modals[newModalName]) // Open new modal
    }
})
const burgerActive = ref(false)
const toggleBurger = () => {
    burgerActive.value = !burgerActive.value

    if (burgerActive.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}
const width = window.innerWidth
</script>

<template>
    <div v-if="width >= 768" class="header">
        <div class="container mx-auto flex flex-wrap justify-between items-center p-6 nav-menu">
            <div class="logo flex-1">
                <img src="/images/logo.svg" alt="Application logo">
            </div>
            <nav class="navigation flex-1">
                <ul class="flex">
                    <Link :href="route('customers')">
                        <li class="text-paleblue font-bold py-1 px-4 cursor-pointer">Заказчикам</li>
                    </Link>
                    <Link :href="route('owners')">
                        <li class="text-paleblue font-bold py-1 px-4 cursor-pointer whitespace-nowrap">Владельцу
                            канала
                        </li>
                    </Link>
                    <li class="text-paleblue select-none font-bold py-1 px-4 cursor-pointer flex gap-1">Сервисы <i class="arrow-circle-down"></i></li>
                </ul>
            </nav>
            <div class="flex flex-1 justify-end">
                <ul class="flex items-center gap-1 text-paleblue font-bold">
                    <li><img src="/images/house-user.svg" alt="home"></li>
                    <li class="flex">
                        <span class="hover:text-violet-300 cursor-pointer" @click.prevent="openModalWithName('register')">Регистрация</span>
                        /
                        <span class="hover:text-violet-300 cursor-pointer" @click.prevent="openModalWithName('login')">Войти</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div v-else class="header">
        <div class="container mx-auto flex flex-wrap justify-between items-center p-3 nav-menu">
            <div class="flex  gap-x-4 items-center z-[4]">
                <div @click.prevent="toggleBurger" class="burger-icon p-2">
                    <div id="nav-icon3" :class="{ open: burgerActive }">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <div class="logo flex-1">
                    <img class="w-8" src="/images/logo.svg" alt="Application logo">
                </div>
            </div>
            <div @click.prevent="openModalWithName('register')">
                <img class="w-10" src="/images/house-user.svg" alt="home">
            </div>
        </div>
    </div>
    <Transition>
    <div v-show="burgerActive" :class="{ active: burgerActive }" class="header-nav px-2">
        <div class="flex gap-x-3 pt-8 text-white text-sm font-bold font-['Open Sans'] leading-tight">
            <button @click.prevent="router.visit(route('customers'))" class="customers-btn w-full py-2">Заказчикам</button>
            <button @click.prevent="router.visit(route('owners'))" class="owners-btn w-full py-2">Владельцу канала</button>
        </div>
        <div class="justify-start items-center gap-1 inline-flex mt-12">
            <div class="text-violet-100 text-base font-bold font-['Open Sans'] leading-tight">Сервисы</div>
            <img class="w-9" src="/images/arrow-circle-down.svg" alt="" srcset="">
        </div>
        <div class="flex mt-4">
            <div class="nav-block rounded border p-8 border-violet-100 backdrop-blur-xl">
                <div class="flex-col justify-start items-start gap-2.5 inline-flex">
                    <div class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Каталог каналов</div>
                    <div class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Каталог ботов</div>
                    <div class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Готовые предложения</div>
                    <div class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Аналитика Telegram-каналов</div>
                </div>
                <div class="flex justify-center">
                <button class="text-center px-6 py-4 mt-5 bg-deepblue rounded-full shadow-inner border border-white border-opacity-10 justify-center items-center gap-2.5 inline-flex text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                    Купить размещение
                </button>
                </div>
            </div>
        </div>
        <div class="mt-24 justify-start items-center gap-2 inline-flex">
            <img src="/images/phone.svg" alt="phone" srcset="">
            <div class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-normal">+7 (495) 818-62-50</div>
        </div>
        <div class="flex gap-x-4 mt-5">
            <div class="flex flex-col justify-center items-center">
            <img class="glow_icon" src="/images/telegram.svg" alt="telegram">
            <div class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Telegram</div>
            </div>
            <div class="flex flex-col justify-center items-center">
            <img class="glow_icon" src="/images/message.svg" alt="message">
            <div class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Почта</div>
            </div>
        </div>
    </div>
    </Transition>
</template>

<style lang="scss" scoped>
.header {
    border-bottom: 1px solid rgba(234, 224, 255, 0.3);
}
.header-nav{
    position: absolute;
    width: 100vw;
    height: 100vh;
  top: 0;
    background: linear-gradient(210deg,rgba(20, 7, 50, 0.9) 30%,rgba(82, 70, 105,0.9));
    backdrop-filter: blur(7px);
    z-index: 3;
    transition: all 0.5s;
    padding-top: 65px;
}
.v-enter-active,
.v-leave-active {
  transition: all 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
.nav-block{
    background: linear-gradient(20deg,rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1));

}
.customers-btn{
    border-radius: 100px;
    border: 1px solid rgba(255, 255, 255, 0.10);
    background: #171961;
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
}
.owners-btn{
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
                border: 1px solid rgba(255, 255, 255, 0.10);
                background: #171961;
                box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
            }
        }
    }
}
.burger-icon{
    width: 40px;
    height: 40px;
    border-radius: 100px;
    border: 1px solid #565656;
    background: rgba(255, 255, 255, 0.20);
    cursor: pointer;
#nav-icon3 {
    position: relative;
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
    -webkit-transition: .5s ease-in-out;
    -moz-transition: .5s ease-in-out;
    -o-transition: .5s ease-in-out;
    transition: .5s ease-in-out;
}

#nav-icon3 span {
    display: block;
    position: absolute;
    height: 3px;
    width: 100%;
    background: #EAE0FF;
    border-radius: 9px;
    opacity: 1;
    left: 0;
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
    -webkit-transition: .25s ease-in-out;
    -moz-transition: .25s ease-in-out;
    -o-transition: .25s ease-in-out;
    transition: .25s ease-in-out;
}
/* Icon 3 */

#nav-icon3 span:nth-child(1) {
    top: 0px;
}

#nav-icon3 span:nth-child(2),#nav-icon3 span:nth-child(3) {
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
</style>
