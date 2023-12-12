<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import OrderCard from "@/Components/Dashboard/OrderCard.vue";
import {onMounted} from "vue";
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";
import {useOrdersStore} from "@/stores/orders.js";


onMounted(() => getOrders())
const ordersStore = useOrdersStore()
const getOrders = (page = 1) => {
    ordersStore.getOrders(page)
}
</script>

<template>
    <AppLayout>
        <div class="py-20 text-center">
            <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Заявки</h1>
            <div class="flex flex-col gap-y-4 mt-8">
                <template v-if="ordersStore.orders" v-for="(order, index) in ordersStore.orders.data" :key="index">
                    <order-card :order="order"/>
                </template>
            </div>
            <div class="flex justify-center">
                <TailwindPagination @pagination-change-page="getOrders" :data="ordersStore.orders"  :limit="3" :active-classes="['bg-blue-950', 'rounded-full', 'shadow-inner', 'border', 'border-white', 'border-opacity-10', 'text-white', 'text-base', 'font-bold', 'font-[\'Open Sans\']', 'leading-tight']" :itemClasses="['border-none', 'text-violet-100', 'text-base', 'font-normal', 'font-[\'Inter\']', 'leading-normal',]" >
                    <template v-slot:prev-nav>
                        <p class="text-center text-violet-100 text-base font-normal font-['Inter'] leading-normal">Назад</p>
                    </template>
                    <template v-slot:next-nav>
                        <p class="text-center text-violet-100 text-base font-semibold font-['Inter'] leading-snug">Вперёд</p>
                    </template>
                </TailwindPagination>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">

</style>
