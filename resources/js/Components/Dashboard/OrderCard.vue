<script setup>
import {computed, ref} from "vue";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiCheck, mdiClose, mdiEyeOutline, mdiForumOutline} from "@mdi/js";
import {openModal, pushModal} from "jenesius-vue-modal";
import Mission from "@/Components/Dashboard/Mission.vue";
import { useLoadingBar, useMessage} from "naive-ui";
import CancelOrder from "@/Components/Dashboard/CancelOrder.vue";
import ToCheck from "@/Components/Dashboard/ToCheck.vue";
import {useOrdersStore} from "@/stores/OrdersStore.js";
import Messenger from "@/Components/Messenger/Messenger.vue"

const props = defineProps({
    order: Object,
    isCard: {
        type: Boolean,
        default: true
    }
})

const ordersStore = useOrdersStore()
const loading = useLoadingBar()
const isLoading = ref(false);
const openMission = () => {
    openModal(Mission, {order: props.order})
}
const decline = () => {
    openModal(CancelOrder, {orderId: props.order.id})
}

const toCheck = () => {
    pushModal(ToCheck, {orderId: props.order.id})
}

const message = useMessage()
const accept = async () => {
    loading.start()
    isLoading.value = true
    await axios.patch(route('order.accept', {orderItemId: props.order.id}))
        .then(response => {
            loading.finish()
            isLoading.value = false
            message.success(response.data.message);
            ordersStore.getOrders()
        })
        .catch(error => {
            loading.error()
            console.log(error);
        });
}
const canAccept = computed(() => !isLoading.value && !['accepted', 'declined', 'check', 'finished'].includes(props.order.status))
const canDecline = computed(() => ['pending'].includes(props.order.status))


const formatDateTime = (dateTime, options) => {
    return dateTime.toLocaleString('ru-RU', options);
};

let postDate = computed(() => props.order.post_date);

const [date, time] = postDate.value.split(' ');

let [year, month, day] = date.split('-');
let pubDay = day + '.' + month;

let [hours, minutes, seconds] = time.split(':');

let originalDate = new Date(postDate.value);
let newDate = new Date(originalDate);
newDate.setHours(newDate.getHours() + 2);

const newTime = newDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

const pubDateOptions = { day: 'numeric', month: 'long' };
const pubTimeOptions = { hour: 'numeric', minute: 'numeric', hour12: false };

const formattedPubDate = formatDateTime(originalDate, pubDateOptions);
const formattedPubTime = formatDateTime(originalDate, pubTimeOptions);

let pubDateEnd = new Date(props.order.post_date_end);

const formattedPubDateEnd = formatDateTime(pubDateEnd, pubDateOptions);
const formattedPubTimeEnd = formatDateTime(pubDateEnd, pubTimeOptions);

const pubTime = `${formattedPubTime}-${newTime}`;

const openMessenger = () => {
  pushModal(Messenger)
}

</script>

<template>
    <div class="order_card">
        <div class="p-4 order_card-container">
            <div class="grid">
                <div>
                    <div class="flex gap-x-4">
                        <img class="rounded-full avatar" :src="order.channel.channelAvatar" alt=""/>
                        <div class="text-start text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">
                            <h1>{{order.channel.channel_name}}</h1>
                            <p class="text-sm normal">тип канала: {{order.channel.topic.title}}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between border-t-2 border-violet-100 border-opacity-40 py-4">
                            <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Подписчики</p>
                            <h1 class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">146 774</h1>
                        </div>
                        <div class="flex justify-between border-t-2 border-violet-100 border-opacity-40 py-4">
                            <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">14/07/2023</p>
                            <h1 class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">#{{order.id}}</h1>
                        </div>
                        <div class="flex justify-between border-t-2 border-violet-100 border-opacity-40 py-4">
                           <div class="flex">
                               <div class="flex flex-col items-end">
                                   <p class="text-violet-100 text-xs font-normal font-['Poppins'] leading-none">Статус заявки</p>
                                   <div class="text-violet-100 text-base font-bold font-['Poppins'] leading-tight flex items-center gap-x-2"><base-icon size="30" :path="mdiCheck"/>{{order.status}}</div>
                               </div>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                  <div class="p-0.5 wrapper">
                      <div class="flex flex-col content-center justify-between px-2 py-4 text-start wrapper_container">
                          <div class="flex flex-col gap-y-1 py-1">
                              <div class="text-violet-100 text-xs font-normal font-['Poppins'] leading-none">Формат</div>
                              <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">{{order.format.name}}</div>
                          </div>
                          <div class="flex flex-col gap-y-1 border-t-2 border-violet-100 border-opacity-40 py-1">
                              <div class="text-violet-100 text-xs font-normal font-['Poppins'] leading-none">День публикации</div>
                              <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">{{pubDay}}</div>
                          </div>
                          <div class="flex flex-col gap-y-1 border-t-2 border-violet-100 border-opacity-40 py-1">
                              <div class="text-violet-100 text-xs font-normal font-['Poppins'] leading-none">Время публикации (UTC +3)</div>
                              <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">{{pubTime}}</div>
                          </div>
                          <div class="flex flex-col gap-y-1 border-t-2 border-violet-100 border-opacity-40 py-1">
                              <div class="text-violet-100 text-xs font-normal font-['Poppins'] leading-none">Цена</div>
                              <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">{{order.price}}&nbsp;₽</div>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="flex flex-col justify-center">
                    <template v-if="order.status === 'pending'">
                        <div class="text-center">

                        </div>
                    </template>
                    <template v-if="order.status === 'check'">
                        <div class="text-center text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">
                            Вы отправили пост на проверку
                        </div>
                    </template>
                    <template v-else-if="order.status === 'accepted'">
                        <div class="text-center text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">Разместите пост</div>
                        <div class="text-center py-2 text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">С {{ formattedPubDate }} {{ formattedPubTime }} <br/> По {{formattedPubDateEnd}} {{ formattedPubTimeEnd}}</div>
                        <button @click.prevent="toCheck" class="px-6 inline-block py-3.5 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal rounded-3xl border border-violet-100 transition hover:bg-gray-400">На проверку</button>
                    </template>
                    <template v-else-if="order.status === 'declined'">
                        <div class="text-center text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">{{order.decline_reason}}</div>
                    </template>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center py-6 unwrap px-4 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
            <div class="flex flex-wrap gap-4">
                <button v-if="canAccept" :disabled="isLoading"  @click.prevent="accept" class="flex items-center gap-x-2 rounded-3xl border border-violet-100 px-6 transition py-3.5 hover:bg-gray-400">Принять <BaseIcon size="30" :path="mdiCheck"/></button>
                <button v-if="isCard" @click.prevent="openMission" class="flex items-center gap-x-2 rounded-3xl border border-violet-100 px-6 transition py-3.5 hover:bg-gray-400">Посмотреть задание <BaseIcon size="30" :path="mdiEyeOutline"/></button>
                <button v-if="canDecline" @click.prevent="decline" class="flex items-center gap-x-2 rounded-3xl border border-violet-100 px-6 transition py-3.5 hover:bg-gray-400">Отклонить <BaseIcon size="30" :path="mdiClose"/></button>
            </div>
            <div>
                <button @click.prevent="openMessenger" class="flex items-center gap-x-2 px-6 py-3.5">Чат заявки <BaseIcon size="30" :path="mdiForumOutline"/></button>
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
.order_card{
    border-radius: 0 0 40px 40px;
    border: 1px solid #6522D9;
    background: #301864;

    .order_card-container{
        border-radius: 0;
        background: linear-gradient(to bottom, #131733, #343850);
    }
    .grid{

        .grid-element{
            min-width: 0;
            padding: 20px;
            &:not(:last-child) {
                border-right: 1px solid #6522D9;
            }
        }
        grid-template-columns: 5fr 5fr 3fr;

        @media screen and (max-width: 640px) {
            grid-template-columns: 1fr;
        }
        div{
            .point{
                width: 15px;
                height: 15px;
                border-radius: 50%;
            }
            .avatar{
                width: 80px;
                height: 80px;
                img{
                    border-radius: 50%;
                }
            }
        }
    }
}
.wrap{
    border-top: 1px solid #6522D9;
}
.wrapper{
    height: 100%;
    border-radius:0 40px 40px 40px;
    background: linear-gradient(to right bottom,#161516, #525073,#1B2C4B);
    .wrapper_container{
        border-radius: 0 40px 40px 40px;
        height: 100%;
        background: linear-gradient(to right,#6D7083, #2B2F4A);
    }
}
</style>
