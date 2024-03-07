<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";
import Transactions from "@/Components/Dashboard/Profile/Transactions.vue";
import {onMounted, ref, watch} from "vue";
import {selectThemeOverrides} from "@/themeOverrides.js";
import { NDatePicker, NSelect, useLoadingBar} from "naive-ui";
import {Head, usePage} from "@inertiajs/vue3";

const page = usePage();
const appointmentInUrl = page.props.ziggy.query.appointment;

const transactions = ref([])
const appointmentVal = ref(appointmentInUrl ? appointmentInUrl : '');
const statusValue = ref('');
const loading = useLoadingBar()
const range = ref([118313526e4, Date.now()])


const getTransactions = async (page = 1, appointmentVal = '', statusValue = '') => {
    loading.start()
    let url = `${route('transactions.get')}?page=${page}&appointment=${appointmentVal}&status=${statusValue}&startDate=${new Date(range.value[0]).toJSON()}&endDate=${new Date(range.value[1]).toJSON()}`;

    await axios.get(url)
        .then(response => {
            transactions.value = response.data;
            loading.finish()
        });
};


const appointment = [
    {
        value: '',
        label: 'Все'
    },
    {
        value: 'replenishment',
        label: 'Пополнение'
    },
    {
        value: 'payout',
        label: 'Вывод'
    },
    {
        value: 'unread',
        label: 'Выполнение проекта'
    },
    {
        value: 'unread',
        label: 'Оплата проекта'
    }
];

const status = [
    {
        value: '',
        label: 'Все'
    },
    {
        value: 'pending',
        label: 'В ожидании'
    },
    {
        value: 'succeeded',
        label: 'Законченный'
    },
    {
        value: 'failed',
        label: 'Неуспешный'
    }
]

onMounted(() => getTransactions(1, appointmentVal.value, statusValue.value));

watch([appointmentVal, statusValue, range], () => {
    getTransactions(1, appointmentVal.value, statusValue.value)
})
</script>

<template>
    <Head>
        <title>История транзакций</title>
    </Head>

    <AppLayout>
        <ProfileLayout>
            <div class="text-center sm:text-left">
                <p class="text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10">История транзакций</p>
            </div>
            <div class="my-12 flex sm:flex-row flex-col sm:p-0 p-2 items-center content-center sm:justify-between justify-center gap-x-2">
                <div class="flex flex-col gap-x-4 w-full">
                    <div class="flex items-center gap-x-2">
                        <p class="text-violet-100 text-lg font-bold font-['Open Sans']">Назначение</p>
                        <n-select :theme-overrides="selectThemeOverrides" v-model:value="appointmentVal" :options="appointment" class="my-4 w-40"/>
                    </div>
                    <div class="flex items-center gap-x-2">
                        <p class="text-violet-100 text-lg font-bold font-['Open Sans']">Статус</p>
                        <n-select :theme-overrides="selectThemeOverrides" v-model:value="statusValue" placeholder="Выберите причину" :options="status" class="my-4 w-40"/>
                    </div>
                </div>
                <div class="w-full">
                    <n-date-picker v-model:value="range" type="daterange" clearable />
                </div>
            </div>
            <Transactions v-if="transactions.data && transactions.data.length" :transactions="transactions.data"/>
            <p v-else class="text-violet-100 text-center text-2xl font-bold font-['Open Sans'] leading-10">Транзакции отсутствуют.</p>
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
