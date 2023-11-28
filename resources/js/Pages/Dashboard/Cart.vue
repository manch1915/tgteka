<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import CatalogChannelCard from "@/Components/Dashboard/CatalogChannelCard.vue";
import {computed, onMounted, ref} from "vue";
import {NInput, NSelect, NTable, useMessage, useLoadingBar} from "naive-ui";
import {inputThemeOverrides, selectThemeOverrides, tableThemeOverrides} from "@/themeOverrides.js";
import axios from "axios";
import {useMainStore} from "@/stores/main.js";
import {router} from "@inertiajs/vue3";

const loadCart = () => {
    return JSON.parse(localStorage.getItem('cart')) || [];
};

const channels = ref(loadCart())
const userPatterns = ref([])
const userPattern = ref(null)
const description = ref('')
const store = useMainStore()

const channelCount = computed(() => channels.value.length);
const placementCount = computed(() => channels.value.reduce((total, channel) => total + channel.count, 0));
const totalSum = computed(() => channels.value.reduce((sum, channel) => {
    const pricePerUnit = channel[channel.format] ? channel[channel.format] : 0;
    return sum + pricePerUnit * channel.count;
}, 0));
const updateChannels = (updatedCart) => {
    channels.value = updatedCart;
};

const loading = useLoadingBar()

const orderPosts = () => {
    loading.start()
  axios.post(route('catalog.channels.orderPosts'), {channels, pattern_id: userPattern.value, description: description.value })
      .then(response => {
          store.subtractFromUserBalance(response.data)
          localStorage.removeItem('cart')
          loading.finish()
          router.visit(route('catalog.channels.index'))
      })
      .catch(c => loading.error())
}

const message = useMessage()

onMounted(() => {
    axios.get(route('user-patterns'))
        .then(response => {
            const patterns = response.data;
            if (!patterns || patterns.length === 0) {
                message.error('у вас нет готовых шаблонов пожалуйста перейдите на страницу мои шаблоны и создайте шаблон')
            }else{
                userPatterns.value.push(...patterns.map((pattern) => ({
                    label: pattern.title,
                    value: pattern.id
                })));
            }
        })
        .catch(error => console.error(error));
})

</script>

<template>
    <AppLayout>
        <div class="py-20 text-center">
            <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Корзина</h1>
        </div>
        <div class="grid grid-cols-[3fr_1fr] gap-x-4">
            <div>
                <div class="channels">
                    <div class="flex flex-col gap-y-4 mt-8">
                        <template v-if="channels" v-for="channel in channels" :key="channel.id">
                            <CatalogChannelCard @cart-updated="updateChannels"  :channel="channel" :count-value="channel.count" :format-value="channel.format"/>
                        </template>
                    </div>
                </div>
            </div>

            <div>
                <div class="my-2">
                    <n-select placeholder="Шаблоны" v-model:value="userPattern" :options="userPatterns" :theme-overrides="selectThemeOverrides"/>
                </div>
                <div class="my-2">
                    <n-input type="textarea" v-model:value="description" :theme-overrides="inputThemeOverrides" placeholder="Описание заказа"/>
                </div>
                <n-table :theme-overrides="tableThemeOverrides" :single-line="false">
                    <thead>
                    <tr>
                        <th>Каналы</th>
                        <td>{{channelCount}}</td>
                    </tr>
                    <tr>
                        <th>Размещения</th>
                        <td>{{placementCount}}</td>
                    </tr>
                    <tr>
                        <th>Подписчики</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Просмотры</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Сумма</th>
                        <th>{{totalSum}}₽</th>
                    </tr>
                    </thead>
                </n-table>
                <button @click.prevent="orderPosts" class="w-full my-4 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal transition bg-purple-600 hover:bg-purple-800 rounded-3xl py-2">
                    Купить размещение
                </button>
            </div>
        </div>
    </AppLayout>
</template>
<style scoped>
    .channels{
        @media screen and (max-width: 640px) {
            padding: 0 15px;
        }
        min-width: 0;
    }
.v-enter-active,
.v-leave-active {
    transition: all 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
