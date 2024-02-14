<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { useCartStore } from "@/stores/CartStore.js";
import {onMounted, reactive, ref, watch} from "vue";
import { loadCart } from "@/channelHelpers.js";
import { mdiCart } from "@mdi/js";
import { NBadge } from "naive-ui";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import { pushModal } from "jenesius-vue-modal";
import Messenger from "@/Components/Messenger/Messenger.vue";

const cartStore = useCartStore()
const page = usePage()

const cart = reactive({ items: [] });
const unreadNotifications = ref(0);

watch(() => cartStore.cartUpdate, () => {
    cart.items = loadCart();
});

onMounted(async () => {
    cart.items = loadCart();
    const response = await axios.get(route('get.unread-notifications-count'));
    unreadNotifications.value = response.data;
})

const openMessenger = () => {
    pushModal(Messenger)
}
</script>

<template>
    <div class="nav-bar p-2">
        <main class="flex justify-evenly items-center w-full">
            <Link :href="route('personal-data')">
                <img src="/images/person.svg" alt="">
            </Link>
            <Link :href="route('adding-channel')">
                <img src="/images/pattern.svg" alt="">
            </Link>
            <NBadge color="#6522d9" :value="unreadNotifications" :max="15">
                <Link :href="route('notifications')">
                    <img src="/images/notification.svg" alt="" class="!w-full !h-full">
                </Link>
            </NBadge>
            <Link v-if="cart.items.length > 0" :href="route('cart')">
                <n-badge  type="info" :value="cart.items.length">
                    <BaseIcon class="text-purple-400" size="25" :path="mdiCart"/>
                </n-badge>
            </Link>
            <img @click.prevent="openMessenger" class="cursor-pointer" src="/images/messenger.svg" alt="">
        </main>
    </div>
</template>

<style scoped lang="scss">
.nav-bar{
    position:fixed;
    bottom:0;
    left:0;
    right:0;
    z-index:1000;

    will-change:transform;
    transform: translateZ(0);
    display:none;


    height: 65px;

    background: #1C1D6D;
    @media screen and (max-width: 640px) {
        display:flex;
    }
    main{
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.10);
        background: #171961;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
        img{
            width: 30px;
            height: 30px;
        }
    }
}
</style>
