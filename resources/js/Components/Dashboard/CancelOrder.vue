<script setup>
import {NSelect, NInput, useMessage, NDatePicker, NConfigProvider, darkTheme, useLoadingBar} from "naive-ui";
import {closeModal} from "jenesius-vue-modal";
import {inputThemeOverrides, selectThemeOverrides} from "@/themeOverrides.js";
import {computed, ref, watch} from "vue";
import {useOrdersStore} from "@/stores/orders.js";

const ordersStore = useOrdersStore()

const props = defineProps({
    orderId: Number
})

const message = useMessage()
const loading = useLoadingBar()
const decline = () => {
    loading.start()
    const data = {
        reason: selectedValue.value,
        orderItemId: props.orderId,
    };

    if (selectedValue.value === 'Другое') {
        data.reason = otherBody.value;
    }

    if (dateSelect.value && timestamp.value && selectedValue.value === 'Нет свободного места. Предложить свою дату.') {
        data['suggested_date'] = timestamp.value;
    }

    axios.patch(route('order.decline', data))
        .then(response => {
            message.error(response.data.message);
            loading.finish()
        })
        .catch(error => {
            console.log(error);
            loading.error()
        });
    ordersStore.getOrders()
    closeModal()
}

const selectedValue = ref('')

const date = new Date();
date.setSeconds(0, 0);
const timestamp = ref(date.getTime() + 24 * 60 * 60 * 1000)

const options = [
    {
        value: 'Нет свободного места. Предложить свою дату.',
        label: 'Нет свободного места. Предложить свою дату.'
    },
    {
        value: 'Нетематическая интеграция',
        label: 'Нетематическая интеграция'
    },
    {
        value: 'Сомнительное содержание поста',
        label: 'Сомнительное содержание поста'
    },
    {
        value: 'Другое',
        label: 'Другое'
    }
];

const other = computed(() => selectedValue.value === 'Другое')
const dateSelect = computed(() => selectedValue.value === 'Нет свободного места. Предложить свою дату.')
const otherBody = ref('');
const disablePastDates = (currentTimestamp) => {

    const currentTimestampNow = Date.now();

    return currentTimestamp < currentTimestampNow;
}
const disableMinutesAndSeconds = (currentTimestamp, { hour } = {}) => {

    const defaultSecond = 0;

    return {
        isSecondDisabled: (second) => second !== defaultSecond,
    };
};

</script>

<template>
    <div class="container">
        <main>
            <div class="top">
                <div class="header">
                    <div class="float-right cursor-pointer" @click.prevent="closeModal()">
                        <img src="/images/Icon-close.svg" alt="">
                    </div>
                    <div class="flex flex-col items-start justify-center gap-y-6 sm:items-center">
                        <h1 class="sm:text-center text-start text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10">Отмена заявки</h1>
                    </div>
                </div>
                <div class="w-1/2 mx-auto">
                    <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight text-center my-8">Выберите причину отказа от проекта</p>
                    <n-select :theme-overrides="selectThemeOverrides" v-model:value="selectedValue" placeholder="Выберите причину" :options="options" class="my-4"/>
                    <n-config-provider :theme="darkTheme">
                        <n-date-picker v-show="dateSelect" v-model:value="timestamp" type="datetime" :is-date-disabled="disablePastDates" :is-time-disabled="disableMinutesAndSeconds"/>
                    </n-config-provider>
                    <n-input v-show="other" v-model:value="otherBody" :theme-overrides="inputThemeOverrides" placeholder="Другое"/>
                    <button @click.prevent="decline" class="w-full py-2 my-2 bg-purple-600 animation hover:bg-purple-800 rounded-3xl text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Подтвердить</button>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped lang="scss">
main{
    width: 100%;
    border-radius: 30px 30px;
    border: 1px solid rgba(101, 34, 217, 1);
    box-shadow: 0 3px 40px 0 rgba(41, 37, 0, 0.10);
}
.top{
    padding: 65px;
    border-radius: 30px;
    background: rgba(7, 12, 41, 1);

    @media screen and (max-width: 640px){
        padding: 10px;
        height: 70vh;
    }
    .overflowing{
        overflow-y: auto;
        height: calc(100% - 35px);
        @media screen and (max-width: 640px){
            height: calc(100% - 75px);
        }
    }
    .grid{
        grid-template-columns: minmax(0, 8fr) minmax(0, 4fr);
    }
}
</style>
