<script setup>
import {computed, ref, defineEmits} from "vue";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiBug, mdiCheck, mdiCheckDecagramOutline, mdiForumOutline, mdiForumPlusOutline} from "@mdi/js";
import {pushModal} from "jenesius-vue-modal";
import Report from "@/Components/Dashboard/Report.vue";
import Review from "@/Components/Dashboard/Review.vue";
import { useLoadingBar, useMessage} from "naive-ui";
import Messenger from "@/Components/Messenger/Messenger.vue"

const props = defineProps({
    order: Object,
    isCard: {
        type: Boolean,
        default: true
    }
})

const loading = useLoadingBar()
const isLoading = ref(false);

const message = useMessage()

const canReport = computed(() => !isLoading.value && ['accepted', 'check', 'checked'].includes(props.order.status))
const canReview = computed(() => !isLoading.value && ['finished'].includes(props.order.status))
const canAccept = computed(() => !isLoading.value && ['check'].includes(props.order.status))

const formatDateTime = (dateTime, options) => {
    return dateTime.toLocaleString('ru-RU', options);
};

let createdAt = computed(() => {
    let date = new Date(props.order.created_at);
    return date.toLocaleDateString('en-GB'); // 'en-GB' uses day/month/year format
});

let postDate = computed(() => props.order.post_date);

const [date, time] = postDate.value.split(' ');

let [year, month, day] = date.split('-');
let pubDay = day + '.' + month;

let [hours, minutes, seconds] = time.split(':');

let originalDate = new Date(postDate.value);
let newDate = new Date(originalDate);
newDate.setHours(newDate.getHours() + 2);

const newTime = newDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

const pubTimeOptions = { hour: 'numeric', minute: 'numeric', hour12: false };

const formattedPubTime = formatDateTime(originalDate, pubTimeOptions);

const pubTime = `${formattedPubTime}-${newTime}`;

const openMessenger = () => {
  pushModal(Messenger)
}

const openReport = () => {
  pushModal(Report, {order_id: props.order.id})
}

const openReview = () => {
  pushModal(Review, {order_id: props.order.id})
}

const emit = defineEmits(['orderAccepted'])

const acceptOrder = () => {
    axios.post(route('accept-order', { order: props.order.id }))
    emit('orderAccepted')
}

</script>

<template>
    <div class="order_card">
        <div class="p-4 order_card-container">
            <div class="grid ">
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
                            <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Подписчики</p>
                            <h1 class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">{{order.channel.participants_count}}</h1>
                        </div>
                        <div class="flex justify-between border-t-2 border-violet-100 border-opacity-40 py-4">
                            <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">{{ createdAt }}</p>
                            <h1 class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">#{{order.id}}</h1>
                        </div>
                        <div class="flex justify-between border-t-2 border-violet-100 border-opacity-40 py-4">
                           <div class="flex">
                               <div class="flex flex-col items-end">
                                   <p class="text-violet-100 text-xs font-normal font-['Open Sans'] leading-none">Статус заявки</p>
                                   <div class="text-violet-100 text-base font-bold font-['Open Sans'] leading-tight flex items-center gap-x-2"><base-icon size="30" :path="mdiCheck"/>
                                       {{ $t('messages.' + order.status) }}</div>
                               </div>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                  <div class="p-0.5 wrapper">
                      <div class="flex flex-col content-center justify-between px-2 py-4 text-start wrapper_container">
                          <div class="flex flex-col gap-y-1 py-1">
                              <div class="text-violet-100 text-xs font-normal font-['Open Sans'] leading-none">Формат</div>
                              <div class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">{{order.format.name}}</div>
                          </div>
                          <div class="flex flex-col gap-y-1 border-t-2 border-violet-100 border-opacity-40 py-1">
                              <div class="text-violet-100 text-xs font-normal font-['Open Sans'] leading-none">День публикации</div>
                              <div class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">{{order.near_future ? 'В ближайшее время' : pubDay}}</div>
                          </div>
                          <div class="flex flex-col gap-y-1 border-t-2 border-violet-100 border-opacity-40 py-1">
                              <div class="text-violet-100 text-xs font-normal font-['Open Sans'] leading-none">Время публикации (UTC +3)</div>
                              <div class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">{{order.near_future ? 'В ближайшее время' : pubTime}}</div>
                          </div>
                          <div class="flex flex-col gap-y-1 border-t-2 border-violet-100 border-opacity-40 py-1">
                              <div class="text-violet-100 text-xs font-normal font-['Open Sans'] leading-none">Цена</div>
                              <div class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">{{order.price}}&nbsp;₽</div>
                          </div>
                      </div>
                  </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center sm:py-6 py-3 unwrap sm:px-4 px-2 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
            <div class="flex gap-x-4">
                <button v-if="canReport" :disabled="isLoading"  @click.prevent="openReport" class="flex items-center gap-x-2 rounded-3xl border border-violet-100 sm:px-6 px-2 transition sm:py-3.5 py-3 hover:bg-gray-400">Пожаловаться <BaseIcon size="30" :path="mdiBug"/></button>
                <button v-if="canAccept" :disabled="isLoading"  @click.prevent="acceptOrder" class="flex items-center gap-x-2 rounded-3xl border border-violet-100 sm:px-6 px-2 transition sm:py-3.5 py-3 hover:bg-gray-400">Проверено <BaseIcon size="30" :path="mdiCheckDecagramOutline"/></button>
                <button v-if="canReview" :disabled="isLoading"  @click.prevent="openReview" class="flex items-center gap-x-2 rounded-3xl border border-violet-100 sm:px-6 px-2 transition sm:py-3.5 py-3 hover:bg-gray-400">Оставить отзыв <BaseIcon size="30" :path="mdiForumPlusOutline"/></button>
             </div>
            <div>
                <button @click.prevent="openMessenger" class="flex items-center gap-x-2 sm:px-6 px-2 sm:py-3.5 py-3 sm:text-lg text-base">Чат заявки <BaseIcon size="30" :path="mdiForumOutline"/></button>
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
        grid-template-columns: 5fr 5fr;

        @media screen and (max-width: 1024px) {
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
