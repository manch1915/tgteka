<script setup>
import {usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import ProfileButton from "@/Components/Dashboard/Profile/ProfileButton.vue";

const page = usePage()

const user = computed(() => page.props.auth.user)

const buttons = [
    {
        title: 'Личные данные',
        route: route('personal-data')
    },
    {
        title: 'Общий баланс',
        route: route('total-balance')
    },
    {
        title: 'Пополнение средств',
        route: route('replenishment')
    },
    {
        title: 'Вывод средств',
        route: route('withdraw')
    },
    {
        title: 'История транзакций',
        route: '#'
    },
    {
        title: 'История уведомлений',
        route: '#'
    },
    {
        title: 'Настройка уведомлений',
        route: '#'
    },
    {
        title: 'Изменение пароля',
        route: '#'
    }
]
</script>

<template>
    <div class="mt-20 profile-layout">
        <div>
            <aside
                class="max-w-xs h-auto origin-top-left rounded-tr-2xl rounded-bl-2xl rounded-br-2xl border backdrop-blur-2xl pb-4 mr-10">
                <div class="text-violet-100 text-lg font-normal font-['Roboto Flex'] leading-relaxed py-6 px-3">
                    {{ user.email }}
                </div>
                <profile-button v-for="item in buttons" :title="item.title" :routeHref="item.route"/>
            </aside>
        </div>
        <div class="w-full">
            <slot/>
        </div>
    </div>
</template>

<style scoped lang="scss">
aside {
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
}

.profile-layout {
    display: grid;
    justify-content: start;
    grid-template-columns: 4fr 12fr;
}
</style>
