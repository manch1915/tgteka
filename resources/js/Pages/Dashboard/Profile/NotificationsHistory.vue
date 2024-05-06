<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import {useNotificationStore} from "@/stores/NotificationsStore.js";
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";
import Notifications from "@/Components/Dashboard/Profile/Notifications.vue";
import {Head} from "@inertiajs/vue3";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiArrowLeftBold, mdiArrowRightBold} from "@mdi/js";
import {onMounted, onUnmounted, ref} from "vue";

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
                <TailwindPagination @pagination-change-page="notificationsStore.getNotifications" :data="notificationsStore.notifications"  :limit="2" :active-classes="['bg-blue-950', 'rounded-full', 'shadow-inner', 'border', 'border-white', 'border-opacity-10', 'text-white', 'text-base', 'font-bold', 'font-[\'Open Sans\']', 'leading-tight']" :itemClasses="['border-none', 'text-violet-100', 'text-base', 'font-normal', 'font-[\'Inter\']', 'leading-normal',]" >
                    <template v-slot:prev-nav>
                        <p v-if="windowWidth >= 640" class="text-center text-violet-100 text-base font-normal font-['Inter'] leading-normal">Назад</p>
                        <BaseIcon v-else size="15" :path="mdiArrowLeftBold"/>
                    </template>
                    <template v-slot:next-nav>
                        <p  v-if="windowWidth >= 640" class="text-center text-violet-100 text-base font-semibold font-['Inter'] leading-snug">Вперёд</p>
                        <BaseIcon v-else size="15" :path="mdiArrowRightBold"/>
                    </template>
                </TailwindPagination>
            </div>
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss">

</style>
