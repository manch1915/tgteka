<script setup>
import { NSelect, NDatePicker, useMessage, NSpace, NSwitch } from 'naive-ui'
import { datePickerThemeOverrides, selectCatalogThemeOverrides } from "@/themeOverrides.js";
import { computed, ref, watch } from "vue";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import { mdiHeartOutline, mdiCartPlus, mdiHeart, mdiCartRemove } from "@mdi/js";
import { Link } from "@inertiajs/vue3";
import { saveCart, loadCart, isInCart as checkInCart, generateFormatArray } from "@/utilities/cartUtilities.js";
import { useCartStore } from "@/stores/CartStore.js";

const props = defineProps({
    channel: Object,
    timestamp: {
        default: () => {
            const date = new Date();
            date.setSeconds(0, 0);
            return date.getTime() + 24 * 60 * 60 * 1000
        },
        required: false,
    },
    isCart: {
        type: Boolean,
        default: false,
        required: false,
    }
});

const cartUpdateKey = ref(0);
const fav = computed(() => props.channel.isFav);
const wrap = ref(false);
const message = useMessage();

const format = computed(() => generateFormatArray(props.channel));
const formatValue = ref(props.channel.format || format.value[0]?.value);

const emit = defineEmits(["cartChanged", "cartUpdated"]);
const cartStore = useCartStore();

const removeCart = (cart, index, channel) => {
    cart.splice(index, 1);
    message.info(`Канал ${channel.channel_name} был удален из корзины.`);
    saveCart(cart);
    emit("cartUpdated", cart);
};

const toggleChannelInCart = (channel) => {
    const cart = loadCart();
    const index = cart.findIndex((ch) => ch.id === channel.id);

    if (index > -1 && formatValue.value === cart[index].format) {
        removeCart(cart, index, channel);
    } else {
        updateCart(cart, channel, formatValue.value, timestamp.value, nearFuture.value);
    }

    cartStore.cartUpdate++;
    cartUpdateKey.value++;
};

const count = [
    { label: '1', value: 1 },
    { label: '2', value: 2 },
    { label: '3', value: 3 },
    { label: '4', value: 4 },
];

const totalPrice = computed(() => {
    const selectedFormat = format.value.find((item) => item.value === formatValue.value);
    return selectedFormat ? props.channel[selectedFormat.value] : 0;
});

const isInCart = (channel) => {
    const dummy = cartUpdateKey.value;
    return checkInCart(channel);
};

const addChannelToFavorites = async (channel) => {
    try {
        const response = await axios.post(route("catalog.channels.favorite"), { channel_id: channel.id });

        if (response.data.status === "success") {
            const isFav = response.data.message.includes("added");
            const operation = isFav ? "добавлен в избранное" : "удален из избранных";
            message.info(`Канал ${channel.channel_name} ${operation}`);
            props.channel.isFav = isFav;
        } else {
            message.error("Возникла проблема с добавлением канала в избранное.");
        }
    } catch (error) {
        message.error("Произошла ошибка: ", error);
    }
};

const updateCart = (cart, channel, format, time, nearFuture) => {
    const channelIndex = cart.findIndex((ch) => ch.id === channel.id);

    if (channelIndex > -1) {
        cart[channelIndex] = Object.assign({}, channel, { format: format, timestamp: time, nearFuture: nearFuture });
        message.info(`Канал ${channel.channel_name} было обновлено в корзине.`);
    } else {
        const channelData = Object.assign({}, channel, { format: format, timestamp: time, nearFuture: nearFuture });
        cart.push(channelData);
        message.info(`Канал ${channel.channel_name} был добавлен в корзину.`);
    }

    saveCart(cart);
    emit("cartUpdated", cart);
};

const timestamp = ref(props.timestamp);

const disablePastDates = (currentTimestamp) => {
    // Get the current date's start timestamp (midnight)
    const startOfToday = new Date();
    startOfToday.setHours(0, 0, 0, 0);
    const startOfTodayTimestamp = startOfToday.getTime();

    // Disable dates before today
    return currentTimestamp < startOfTodayTimestamp;
};


const disableMinutesAndSeconds = ({ hour, minute }) => {
    const now = new Date();
    const currentHour = now.getHours();
    const currentMinute = now.getMinutes();

    // If the selected hour is the current hour, disable past minutes
    const isMinuteDisabled = (minute) => hour === currentHour && minute < currentMinute;

    // Disable all seconds except 0
    const isSecondDisabled = (second) => second !== 0;

    return { isMinuteDisabled, isSecondDisabled };
};

const nearFuture = ref(false);

// Initialize nearFuture from cart if present
const cart = loadCart();
const cartItem = cart.find((ch) => ch.id === props.channel.id);
if (cartItem) {
    nearFuture.value = cartItem.nearFuture;
}
watch(timestamp, (newValue) => {
    let cart = loadCart();
    if (isInCart(props.channel)) {
        updateCart(cart, props.channel, formatValue.value, newValue, nearFuture.value);
    }
});
watch(nearFuture, (newValue) => {
    let cart = loadCart();
    if (isInCart(props.channel)) {
        updateCart(cart, props.channel, formatValue.value, timestamp.value, newValue);
    }
});
watch(formatValue, (newValue) => {
    let cart = loadCart();
    if (isInCart(props.channel)) {
        updateCart(cart, props.channel, newValue, timestamp.value, nearFuture.value);
    }
});
</script>

<template>
    <div class="channel_card">
        <div class="channel_card-container cursor-pointer">
            <Link :href="route('catalog.channels.show', channel.slug)" class="block h-full">
                <div class="flex flex-wrap items-center">
                    <div class="flex sm:w-1/2 w-full">
                        <div class="flex flex-col items-center justify-center gap-y-3 grid-element">
                            <div class="avatar mb-auto">
                                <img :src="channel?.avatar" alt="avatar">
                            </div>
                        </div>
                        <div class="flex-1 grid-element">
                            <div class="flex flex-col justify-between gap-y-2">
                                <h1 class="text-white text-xl font-bold font-['Open Sans'] !leading-tight">{{ channel?.channel_name }}</h1>
                                <p class="text-white box-content line-clamp-3  text-sm font-normal font-['Open Sans'] break-all leading-tight">{{ channel?.description }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 h-full sm:border-none border-t border-[#6522D9] p-4 sm:w-1/2 w-full items-stretch justify-evenly">
                        <div class="sm:border-x-[1px] h-full w-full border-[#6522D9] flex flex-col items-center justify-center">
                            <div class="flex h-full flex-col items-center justify-around text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                                <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Подписчики</p>
                                <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">{{ channel?.statistics?.participants_count }}</p>
                            </div>
                        </div>
                        <div class="sm:border-r-[1px] h-full w-full border-[#6522D9] flex-col items-center justify-center">
                            <div class="flex h-full flex-col items-center justify-around text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                                <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Просмотры</p>
                                <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">{{ channel?.statistics?.avg_post_reach }}</p>
                            </div>
                        </div>
                        <div class="flex h-full w-full flex-col items-center justify-center">
                            <div class="flex h-full flex-col items-center justify-around text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                                <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">CPМ</p>
                                <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">
                                    {{ channel?.cpm }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </Link>
        </div>
        <div class="flex flex-wrap items-center sm:justify-between p-6 unwrap gap-y-2">
            <div class="flex flex-wrap sm:w-auto w-full items-center gap-x-4">
                <div class="flex flex-col items-start gap-y-1">
                    <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Формат</p>
                    <n-select class="w-24" v-model:value="formatValue" @update-value="emit('cartChanged');" :theme-overrides="selectCatalogThemeOverrides" placeholder="" :options="format" />
                </div>
                <h1 class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">{{ totalPrice }} ₽</h1>
            </div>
            <div v-show="isCart">
                <n-space vertical justify="center" align="center">
                    <n-date-picker
                        :disabled="nearFuture"
                        :theme-overrides="datePickerThemeOverrides"
                        v-model:value="timestamp"
                        default-time="12:00:00"
                        type="datetime"
                        :is-date-disabled="disablePastDates"
                        :is-time-disabled="disableMinutesAndSeconds"
                        :actions="['confirm']"
                    />

                    <div class="flex gap-x-2">
                        <p class="text-white box-content line-clamp-3 text-sm font-normal font-['Open Sans'] break-all leading-tight">В ближайшее время</p>
                        <n-switch v-model:value="nearFuture" />
                    </div>
                </n-space>
            </div>
            <div class="flex items-center justify-between text-violet-100 gap-x-2.5">
                <Link :href="route('catalog.channels.show', channel.slug)" class="text-violet-100 text-xs font-normal font-['Open Sans'] underline leading-none">Подробнее о канале</Link>
                <div class="pl-2 cursor-pointer" @click="addChannelToFavorites(channel)">
                    <BaseIcon size="25" :path="fav ? mdiHeart : mdiHeartOutline" />
                </div>
                <div class="cursor-pointer" @click="toggleChannelInCart(channel)">
                    <BaseIcon size="25" :path="isInCart(channel) ? mdiCartRemove : mdiCartPlus" />
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped lang="scss">
.expand-leave-active,
.expand-enter-active {
    transition: all 350ms ease;
    overflow: hidden;
}

.expand-enter-to,
.expand-leave-from {
    height: 642px;
}

.expand-enter-from,
.expand-leave-to {
    height: 0;
}
.message-2{
    width: 24px;
    height: 24px;
    background-color: rgba(234, 224, 255, 1);
    -webkit-mask: url(/images/message-2.svg) no-repeat center;
    mask: url(/images/message-2.svg) no-repeat center;
    transition: background-color 0.5s;

}
.message-2-container:hover{
    .message-2{
        background-color: rgba(135, 41, 255, 1);
    }
}
.card{
    width: 24px;
    height: 24px;
    background-color: rgba(234, 224, 255, 1);
    -webkit-mask: url(/images/card.svg) no-repeat center;
    mask: url(/images/card.svg) no-repeat center;
    transition: background-color 0.5s;

}
.card-container:hover{
    .card{
        background-color: rgba(135, 41, 255, 1);
    }
}
.noun-bar{
    width: 24px;
    height: 24px;
    background-color: rgba(234, 224, 255, 1);
    -webkit-mask: url(/images/noun-bar-charts.svg) no-repeat center;
    mask: url(/images/noun-bar-charts.svg) no-repeat center;
    transition: background-color 0.5s;

}
.noun-bar-container:hover{
    .noun-bar{
        background-color: rgba(135, 41, 255, 1);
    }
}
.channel_card{
    border-radius: 0 40px 40px 40px;
    border: 1px solid #6522D9;
    .unwrap{
        border-radius: 0 0 40px 40px;

        background: #301864;
    }
    .channel_card-container{
        border-radius: 0 40px 0 0;
        background: linear-gradient(to bottom, #131733, #343850);
    }
    .flex{
        .grid-element{
            min-width: 0;
            padding: 20px;
            &:not(:last-child) {
                border-right: 1px solid #6522D9;
            }
        }
        div{
            .point{
                width: 15px;
                height: 15px;
                border-radius: 50%;
            }
            .avatar{
                width: 60px;
                height: 60px;
                img{
                    border-radius: 50%;
                    height: 100%;
                    width: 100%;
                }
            }
        }
    }
}
.wrap{
    border-top: 1px solid #6522D9;
}
.watch,.edit {
    padding: 15px 25px;
    border-radius: 30px;
    border: 1px solid #6522D9;
    transition: background-color .5s;
    &:hover{
        background-color: #6522D9;
    }
}
.orders {
    padding: 15px 25px;
    border-radius: 30px;
    background: rgb(135, 41, 255);
    border: 1px solid transparent;
    transition: background-color .5s;
    &:hover{
        background-color: transparent;
        border: 1px solid #6522D9;

    }
}
.rate_catalog{
    border-radius: 30px;
    background: #363B59;
    padding: 4px 10px;
}
.inkarrow{
    width: 24px;
    height: 24px;
    background: url("/images/inkarrow.svg");
}
.eye{
    width: 24px;
    height: 24px;
    background: url("/images/eye.svg");
}
.arrow-down{
    width: 24px;
    height: 24px;
    background: url("/images/arrow-down.svg");
}
</style>
