<script setup>
import {Link, router, usePage} from '@inertiajs/vue3'
import {closeModal} from "jenesius-vue-modal";
import {ref, watchEffect} from "vue";
import {useMainStore} from "@/stores/main.js";
import AnimatedNumber from "@/Components/Dashboard/AnimatedNumber.vue";

closeModal()

const store = useMainStore()
const page = usePage()

watchEffect(() => {
    if (page.props.auth.user && page.props.auth.user.balance !== store.userBalance) {
        store.setUserBalance(page.props.auth.user.balance);
    }
});

const burgerActive = ref(false)

const toggleBurger = () => {
    burgerActive.value = !burgerActive.value

    if (burgerActive.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}
</script>

<template>
    <header>
        <div class="container mx-auto sm:py-6 sm:border-none border-b-[1px] py-3">
            <div class="flex  justify-between items-center sm:p-0 px-2">
                <div class="flex sm:hidden gap-x-6">
                    <div @click.prevent="toggleBurger" class="burger-icon p-2">
                        <div id="nav-icon3" :class="{ open: burgerActive }">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="flex sm:hidden logo  items-center">
                        <img class="w-6" src="/images/logo.svg" alt="logo">
                        <div class="text-gray-200 text-xl font-bold font-['Open Sans'] leading-10">logo</div>
                    </div>
                </div>
                <div class="logo">
                    <div class="hidden sm:inline-flex px-6 py-3.5 bg-zinc-300 rounded-2xl justify-start items-start gap-2.5 ">
                        <div class="text-slate-900 text-lg font-bold font-['Open Sans'] leading-normal">ЛОГО</div>
                    </div>

                </div>
                <nav class="flex gap-x-3">
                    <div class="balance-elements px-6 py-1">
                        <div class="balance flex items-center gap-x-3">
                            <div class="sm:border-r-[1px] pr-2 flex">
                                <p class="sm:block hidden text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Общий баланс&nbsp;</p>
                                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"> <animated-number :number="store.userBalance"/></p>
                            </div>
                            <div class="sm:border-r-[1px] pr-2 flex items-center gap-x-1">
                                <p class="sm:block hidden text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Пополнить</p>
                                <div><img src="/images/group.svg" alt=""></div>
                            </div>
                            <div class="flex items-center gap-x-1">
                                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">0 ₽</p>
                            </div>
                        </div>
                    </div>
                    <div class="hidden sm:flex interactive items-center gap-x-3">
                        <div class="border-r-[1px] px-5">
                            <img src="/images/messenger.svg" alt="">
                        </div>
                        <div class="border-r-[1px] px-5">
                            <img src="/images/notification.svg" alt="">
                        </div>
                        <div class="border-r-[1px] px-5">
                          <Link :href="route('personal-data')">
                            <img src="/images/person.svg" alt="">
                          </Link>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>
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
    <div class="nav hidden sm:block">
        <div class="container mx-auto">
        <div class="py-10">
            <ul class="flex gap-x-5 justify-center">
                <Link :href="route('patterns')"><li class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal cursor-pointer">Мои шаблоны</li></Link>
                <li class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal cursor-pointer">Мои размещения</li>
                <Link :href="route('catalog.channels.index')"><li class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal cursor-pointer">Каталог каналов</li></Link>
                <Link :href="route('channels')"><li class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal cursor-pointer">Мои каналы</li></Link>
                <Link :href="route('order.index')"><li class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal cursor-pointer">Заявки на размещение</li></Link>
            </ul>
        </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
header {
    background: #1C1D6D;
    @media screen and (max-width: 640px) {
        background: #070C29;

    }
    .balance-elements {
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.10);
        background: #171961;
        box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25) inset;
        @media screen and (max-width: 640px) {
            border-radius: none;
            border: none;
            background: transparent;
            box-shadow: none;
        }
    }
}
.nav{
    background: #0D143A;
    li:hover{
        filter: drop-shadow(0 0 5px rgb(237 233 254));
    }
}
.interactive{
  div{
    img{
      cursor: pointer;
      transition: all .5s;
      &:hover{
        filter: drop-shadow(0 0 5px #8729FF);
        -webkit-filter: drop-shadow(0 0 5px #8729FF);
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
