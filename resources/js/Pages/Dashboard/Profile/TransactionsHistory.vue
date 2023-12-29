<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";
import Transactions from "@/Components/Dashboard/Profile/Transactions.vue";
import { onMounted, ref } from "vue";

const transactions = ref([])
const loading = ref(false)
const getTransactions =  async (page = 1) => {
    let url = route('transactions.get') + `?page=${page}`;
    loading.value = true
    await axios.get(url)
        .then(response => {
            transactions.value = response.data
            loading.value = false
        })
}

onMounted(() => getTransactions())
</script>

<template>
    <AppLayout>
        <ProfileLayout>
            <div class="text-center sm:text-left">
                <p class="text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10">История транзакций</p>
            </div>
            <Transactions :transactions="transactions.data"/>
            <div class="flex justify-center">
                <TailwindPagination @pagination-change-page="getTransactions" :data="transactions"  :limit="3" :active-classes="['bg-blue-950', 'rounded-full', 'shadow-inner', 'border', 'border-white', 'border-opacity-10', 'text-white', 'text-base', 'font-bold', 'font-[\'Open Sans\']', 'leading-tight']" :itemClasses="['border-none', 'text-violet-100', 'text-base', 'font-normal', 'font-[\'Inter\']', 'leading-normal',]" >
                    <template v-slot:prev-nav>
                        <p class="text-center text-violet-100 text-base font-normal font-['Inter'] leading-normal">Назад</p>
                    </template>
                    <template v-slot:next-nav>
                        <p class="text-center text-violet-100 text-base font-semibold font-['Inter'] leading-snug">Вперёд</p>
                    </template>
                </TailwindPagination>
            </div>
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss">

</style>
