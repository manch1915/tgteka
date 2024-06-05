<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import Transactions from "@/Components/Dashboard/Profile/Transactions.vue";
import {onMounted, onUnmounted, ref, watch} from "vue";
import {selectThemeOverrides} from "@/themeOverrides.js";
import { NDatePicker, NSelect, useLoadingBar} from "naive-ui";
import {Head, usePage} from "@inertiajs/vue3";
import CustomPagination from "@/Components/Dashboard/CustomPagination.vue";
import { useWindowWidth } from '@/utilities/windowWidth.js'

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
        label: 'Успешный'
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

const { windowWidth } = useWindowWidth()
</script>

<template>
    <Head>
        <title>История транзакций</title>
    </Head>

    <AppLayout>
        <ProfileLayout>
            <div class="text-center lg:text-left">
                <p class="text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10">История транзакций</p>
            </div>
            <div class="my-12 flex xl:flex-row flex-col sm:p-0 p-2 items-center content-center sm:justify-between justify-center gap-x-2">
                <div class="flex sm:flex-row flex-col gap-x-4 w-full justify-between 2xl:justify-start xl:justify-start">
                    <div class="flex items-center gap-x-2 sm:justify-end justify-end">
                        <p class="text-violet-100 text-lg font-bold font-['Open Sans']">Назначение</p>
                        <n-select :theme-overrides="selectThemeOverrides" v-model:value="appointmentVal" :options="appointment" class="my-4 w-40"/>
                    </div>
                    <div class="flex items-center gap-x-2 sm:justify-end justify-end">
                        <p class="text-violet-100 text-lg font-bold font-['Open Sans']">Статус</p>
                        <n-select :theme-overrides="selectThemeOverrides" v-model:value="statusValue" placeholder="Выберите причину" :options="status" class="my-4 w-40"/>
                    </div>
                </div>
                <div class="sm:flex-[1_1_50%] flex-auto self-end sm:self-end 2xl:self-auto xl:self-auto">
                    <n-date-picker v-model:value="range" type="daterange" clearable />
                </div>
            </div>
            <Transactions v-if="transactions.data && transactions.data.length" :transactions="transactions.data"/>
            <p v-else class="text-violet-100 text-center text-2xl font-bold font-['Open Sans'] leading-10">Транзакции отсутствуют.</p>
            <div class="flex justify-center">
                <CustomPagination @pagination-change-page="getTransactions" :data="transactions"/>
            </div>
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss">

</style>
