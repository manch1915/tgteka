<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import {useNotificationStore} from "@/stores/NotificationsStore.js";
import Notifications from "@/Components/Dashboard/Profile/Notifications.vue";
import {Head} from "@inertiajs/vue3";
import {onMounted, onUnmounted, ref} from "vue";
import CustomPagination from "@/Components/Dashboard/CustomPagination.vue";

const notificationsStore = useNotificationStore()
notificationsStore.getNotifications()
const windowWidth = ref(window.innerWidth);
const updateWidth = () => {
    windowWidth.value = window.innerWidth;
};

onMounted(() => {
    // Add the updateWidth function as a window resize listener
    window.addEventListener("resize", updateWidth);
});

onUnmounted(() => {
    // Remove listener when component is unmounted
    window.removeEventListener("resize", updateWidth);
});
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
