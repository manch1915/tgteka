<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import OrderCard from "@/Components/Dashboard/OrderCard.vue";
import {onMounted} from "vue";
import {useOrdersStore} from "@/stores/OrdersStore.js";
import CustomPagination from "@/Components/Dashboard/CustomPagination.vue";
import {useConversationsStore} from "@/stores/ConversationsStore.js";

onMounted(() => getOrders())
const ordersStore = useOrdersStore()
const getOrders = (page = 1) => {
    ordersStore.getOrders(page)
}
const store = useConversationsStore();
store.getConversations();
</script>

<template>
    <AppLayout>
        <div class="sm:py-20 py-4 text-center px-2">
            <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Заявки</h1>
            <div class="flex flex-col gap-y-4 mt-8">
                <template v-if="ordersStore.orders && ordersStore.orders.data && ordersStore.orders.data.length > 0"
                          v-for="(order, index) in ordersStore.orders.data" :key="index">
                    <order-card :order="order"/>
                </template>
                <p v-else class="text-violet-100 text-center text-2xl font-bold font-['Open Sans'] leading-10">У вас пока нет заявок на размещение.</p>
            </div>
            <div class="flex justify-center">
                <CustomPagination @pagination-change-page="getOrders" :data="ordersStore.orders"/>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">

</style>
