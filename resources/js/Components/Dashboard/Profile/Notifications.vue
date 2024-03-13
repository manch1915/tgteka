<script setup>
import {useNotificationStore} from "@/stores/NotificationsStore.js";
import NotificationsCard from "@/Components/Dashboard/Profile/NotificationsCard.vue";
import {selectThemeOverrides} from "@/themeOverrides.js";
import {NSelect, NSkeleton} from "naive-ui";
import {ref, watchEffect} from "vue";

const notificationsStore = useNotificationStore()

const orderVal = ref('all');
const selectedValue = ref('desc');

const order = [
    {
        value: 'all',
        label: 'Все уведомления'
    },
    {
        value: 'read',
        label: 'Просмотренные'
    },
    {
        value: 'unread',
        label: 'Непросмотренные'
    }
];
const sort = [
    {
        value: 'desc',
        label: 'От новых к старым'
    },
    {
        value: 'asc',
        label: 'От старых к новым'
    }
]

const markAsReadAll = () => {
  axios.post(route('notifications.mark-as-read-all'));
}

watchEffect(() => {
    notificationsStore.getNotifications(1, orderVal.value, selectedValue.value);
});
</script>

<template>
    <div class="sm:my-12 my-6 flex flex-wrap items-center content-center sm:justify-between justify-center">
        <div class="flex gap-x-4 flex-wrap justify-center">
            <div class="w-44">
                <n-select :theme-overrides="selectThemeOverrides" v-model:value="orderVal" :options="order" class="my-4"/>
            </div>
            <div class="w-44">
                <n-select :theme-overrides="selectThemeOverrides" v-model:value="selectedValue" placeholder="Выберите причину" :options="sort" class="my-4"/>
            </div>
        </div>
        <button @click.prevent="markAsReadAll" class="px-6 py-2 text-violet-100 transition hover:text-violet-700 underline hover font-bold font-['Open Sans'] leading-normal rounded-3xl ">пометить все как прочитанное</button>
    </div>
    <div v-if="!notificationsStore.loading" class="flex flex-col gap-y-2 mt-6">
        <notifications-card v-for="notification in notificationsStore.notifications.data" :notification="notification.data"/>
    </div>
    <div v-if="notificationsStore.loading">
        <div class="flex flex-col gap-y-2 mt-6">
            <template v-for="i in 10">
                <n-skeleton height="70px" :sharp="false"/>
            </template>
        </div>
    </div>

</template>

<style scoped lang="scss">

</style>
