<script setup>
import {usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import ProfileButton from "@/Components/Dashboard/Profile/ProfileButton.vue";

const page = usePage()

const username = computed(() => page.props.auth.user.username)

const buttons = [
    {
        title: 'Личные данные',
        route: route('personal-data'),
        icon: '/images/profile/user.svg'
    },
    {
        title: 'Пополнение средств',
        route: route('replenishment'),
        icon: '/images/profile/replenishment.svg'
    },
    {
        title: 'Вывод средств',
        route: route('withdraw'),
        icon: '/images/profile/withdraw.svg'
    },
    {
        title: 'История транзакций',
        route: route('transactions'),
        icon: '/images/profile/transaction-history.svg'
    },
    {
        title: 'История уведомлений',
        route: route('notifications'),
        icon: '/images/profile/book.svg'
    },
    {
        title: 'Настройка уведомлений',
        route: route('notifications-setting'),
        icon: '/images/profile/notification.svg'
    },
    {
        title: 'Изменение пароля',
        route: route('change-password'),
        icon: '/images/profile/password.svg'
    }
]
</script>

<template>
    <div class="sm:mt-20 mt-4 profile-layout">
        <div class="lg:w-auto w-full">
            <aside class="lg:mr-10 mr-4 h-auto lg:max-w-xs origin-top-left rounded-tr-2xl rounded-br-2xl rounded-bl-2xl lg:border pb-4 backdrop-blur-2xl">
                <div class="lg:text-violet-100 text-purple-600 text-lg font-normal font-['Roboto Flex'] leading-relaxed py-2 px-3">
                    {{ username }}
                </div>
                <div class="sm:px-0 px-4 pt-4">
                    <profile-button v-for="item in buttons" :title="item.title" :routeHref="item.route" :icon="item.icon"/>
                </div>
            </aside>
        </div>
        <div class="w-full">
            <slot/>
        </div>
    </div>
</template>

<style scoped lang="scss">
aside {
    background: linear-gradient(38deg, rgb(149 149 149 / 59%) 0%, rgb(208 208 208 / 4%) 100%);
    @media screen and (max-width: 1024px) {
        background: transparent;
    }
    @media (min-width: 1024px) {
        border: #9c72d170 solid 2px;
    }
}
.profile-layout {
    display: grid;
    justify-content: start;
    grid-template-columns: 4fr 12fr;
    padding: 0 10px;
    @media screen and (max-width: 1280px){
        grid-template-columns: 6fr 12fr;
        justify-content: center;
        justify-items: center;
    }
    @media screen and (max-width: 1024px){
        grid-template-columns: 1fr;
        justify-content: center;
        justify-items: center;
    }
}
</style>
