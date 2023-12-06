<script setup>
import {ref} from "vue";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiCheck, mdiClose, mdiEyeOutline, mdiForumOutline} from "@mdi/js";
import {openModal} from "jenesius-vue-modal";
import Mission from "@/Components/Dashboard/Mission.vue";
import { useMessage } from "naive-ui";
import CancelOrder from "@/Components/Dashboard/CancelOrder.vue";

const props = defineProps({
    order: Object
})
const openMission = () => {
    openModal(Mission, {order: props.order})
}

const message = useMessage()
const accept = () => {
    axios.patch(route('order.accept', {orderItemId: props.order.id}))
        .then(response => {
            message.success(response.data.message);
        })
        .catch(error => {
            console.log(error);
        });
}
const decline = () => {
   openModal(CancelOrder, {order: props.order.id})
}
const wrap = ref(false)
</script>

<template>
    <div class="order_card">
        <div class="order_card-container p-4">
            <div class="grid">
                <div>
                    <div class="flex gap-x-4">
                        <img class="avatar rounded-full" :src="order.channel.channelAvatar" alt=""/>
                        <div class="text-start text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">
                            <h1>{{order.channel.channel_name}}</h1>
                            <p class="normal text-sm">тип канала: {{order.channel.topic.title}}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between border-t-2 border-violet-100 border-opacity-40 py-4">
                            <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Подписчики</p>
                            <h1 class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">146 774</h1>
                        </div>
                        <div class="flex justify-between border-t-2 border-violet-100 border-opacity-40 py-4">
                            <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">14/07/2023</p>
                            <h1 class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">#1483252</h1>
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
                      <div class="wrapper_container px-2 py-4 text-start flex flex-col justify-between content-center">
                          <div class="flex flex-col gap-y-1 py-1">
                              <div class="text-violet-100 text-xs font-normal font-['Poppins'] leading-none">Формат</div>
                              <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">{{order.format.name}}</div>
                          </div>
                          <div class="flex flex-col gap-y-1 py-1 border-t-2 border-violet-100 border-opacity-40">
                              <div class="text-violet-100 text-xs font-normal font-['Poppins'] leading-none">День публикации</div>
                              <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">14.07</div>
                          </div>
                          <div class="flex flex-col gap-y-1 py-1 border-t-2 border-violet-100 border-opacity-40">
                              <div class="text-violet-100 text-xs font-normal font-['Poppins'] leading-none">Время публикации (UTC +3)</div>
                              <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">08:00-12:00</div>
                          </div>
                          <div class="flex flex-col gap-y-1 py-1 border-t-2 border-violet-100 border-opacity-40">
                              <div class="text-violet-100 text-xs font-normal font-['Poppins'] leading-none">Цена</div>
                              <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">{{order.price}}&nbsp;₽</div>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="flex flex-col justify-center">
                    <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">Вы отправили пост на проверку</div>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center py-6 unwrap px-4 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
            <div class="flex gap-x-4">
                <button @click.prevent="accept" class="px-6 py-3.5 rounded-3xl border border-violet-100 transition hover:bg-gray-400 flex items-center gap-x-2">Принять <BaseIcon size="30" :path="mdiCheck"/></button>
                <button @click.prevent="openMission" class="px-6 py-3.5 rounded-3xl border border-violet-100 transition hover:bg-gray-400 flex items-center gap-x-2">Посмотреть задание <BaseIcon size="30" :path="mdiEyeOutline"/></button>
                <button @click.prevent="decline" class="px-6 py-3.5 rounded-3xl border border-violet-100 transition hover:bg-gray-400 flex items-center gap-x-2">Отклонить <BaseIcon size="30" :path="mdiClose"/></button>
            </div>
            <div>
                <button class="px-6 py-3.5 flex items-center gap-x-2">Чат заявки <BaseIcon size="30" :path="mdiForumOutline"/></button>
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
        border-radius: 0 40px 0 0;
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
