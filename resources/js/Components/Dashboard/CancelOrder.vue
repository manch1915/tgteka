<script setup>
import {NSelect, NInput, useMessage} from "naive-ui";
import {closeModal} from "jenesius-vue-modal";
import {inputThemeOverrides, selectThemeOverrides} from "@/themeOverrides.js";
import {computed, ref} from "vue";

const message = useMessage()
const decline = () => {
    axios.patch(route('order.decline', {orderId: props.order.id}))
        .then(response => {
            message.error(response.data.message);
        })
        .catch(error => {
            console.log(error);
        });
}

const selectedValue = ref('')

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
                    <n-input v-show="other" :theme-overrides="inputThemeOverrides" placeholder="Другое"/>
                    <button class="w-full py-2 my-2 bg-purple-600 animation hover:bg-purple-800 rounded-3xl text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Подтвердить</button>
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
