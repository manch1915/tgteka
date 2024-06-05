<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import {useNotificationStore} from "@/stores/NotificationsStore.js";
import Notifications from "@/Components/Dashboard/Profile/Notifications.vue";
import {Head} from "@inertiajs/vue3";
import CustomPagination from "@/Components/Dashboard/CustomPagination.vue";
import { useWindowWidth } from '@/utilities/windowWidth.js'

const notificationsStore = useNotificationStore()
notificationsStore.getNotifications()

const { windowWidth } = useWindowWidth()

</script>

<template>
    <Head>
        <title>История уведомлений</title>
    </Head>

    <AppLayout>
        <ProfileLayout>
            <div class="text-center lg:text-left">
                <p class="text-violet-100 sm:text-4xl text-xl font-bold font-['Open Sans'] leading-10">История уведомлений</p>
            </div>
            <Notifications/>
            <div class="flex justify-center">
                <CustomPagination @pagination-change-page="notificationsStore.getNotifications" :data="notificationsStore.notifications"/>
            </div>
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss">

</style>
