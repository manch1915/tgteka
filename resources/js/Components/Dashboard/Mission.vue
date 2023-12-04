<script setup>
import {closeModal} from "jenesius-vue-modal";
import {mdiCheck, mdiClose, mdiEyeOutline, mdiForumOutline} from "@mdi/js";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {inputThemeOverrides} from "@/themeOverrides.js";
import {NInput} from "naive-ui";
import {ref} from "vue";

const props = defineProps({
    order: Object
})

const uploadedImageUrl = ref(props.order.orderPattern.patternMedia || '/images/photo.png');

const sendPostByBot = () => {
    axios.post(route('order.send-pattern-by-bot'), {pattern: props.order.orderPattern})
        .then()
}

</script>

<template>
    <div class="scrollbar container overflow-y-auto h-full">
        <main>
            <div class="top">
                <div class="header">
                    <div class="float-right cursor-pointer" @click.prevent="closeModal()">
                        <img src="/images/Icon-close.svg" alt="">
                    </div>
                    <div class="flex flex-col items-start justify-center gap-y-6 sm:items-center">
                        <h1 class="sm:text-center text-start text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10">Посмотреть задание</h1>
                    </div>
                </div>
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
                                        <h1 class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">#1483252</h1>
                                    </div>
                                    <div class="flex justify-between border-t-2 border-violet-100 border-opacity-40 py-4">
                                        <div class="flex">
                                            <div class="flex flex-col items-end">
                                                <p class="text-violet-100 text-xs font-normal font-['Poppins'] leading-none">Статус заявки</p>
                                                <div class="text-violet-100 text-base font-bold font-['Poppins'] leading-tight flex items-center gap-x-2"><base-icon size="30" :path="mdiCheck"/>В работе</div>
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
                                            <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">14.07</div>
                                        </div>
                                        <div class="flex flex-col gap-y-1 border-t-2 border-violet-100 border-opacity-40 py-1">
                                            <div class="text-violet-100 text-xs font-normal font-['Poppins'] leading-none">Время публикации (UTC +3)</div>
                                            <div class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">08:00-12:00</div>
                                        </div>
                                        <div class="flex flex-col gap-y-1 border-t-2 border-violet-100 border-opacity-40 py-1">
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
                            <button class="flex items-center gap-x-2 rounded-3xl border border-violet-100 px-6 transition py-3.5 hover:bg-gray-400">Принять <BaseIcon size="30" :path="mdiCheck"/></button>
                            <button @click.prevent="openMission" class="flex items-center gap-x-2 rounded-3xl border border-violet-100 px-6 transition py-3.5 hover:bg-gray-400">Посмотреть задание <BaseIcon size="30" :path="mdiEyeOutline"/></button>
                            <button class="flex items-center gap-x-2 rounded-3xl border border-violet-100 px-6 transition py-3.5 hover:bg-gray-400">Отклонить <BaseIcon size="30" :path="mdiClose"/></button>
                        </div>
                        <div>
                            <button class="flex items-center gap-x-2 px-6 py-3.5">Чат заявки <BaseIcon size="30" :path="mdiForumOutline"/></button>
                        </div>
                    </div>
                </div>
                <div class="mt-8 grid gap-x-4">
                    <div>
                        <div class="flex justify-between py-4">
                            <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Срок публикации: <span class="text-opacity-60 text-sm font-normal font-['Poppins'] leading-tight">В ближайшее время</span></p>
                            <h1 class="text-violet-100 text-sm font-bold font-['Poppins'] leading-tight">Время публикации: --</h1>
                        </div>
                        <div class="flex justify-between border-y-2 border-violet-100 border-opacity-40 py-4">
                            <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Формат размещения <span class="text-opacity-60 text-sm font-normal font-['Poppins'] leading-tight">{{order.format.name}}</span></p>
                        </div>
                        <div class="py-4">
                            <div class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Ссылка в тексте</div>
                            <n-input class="my-1 py-1.5" placeholder="Ссылка в тексте" :theme-overrides="inputThemeOverrides"/>
                        </div>
                    </div>
                    <div>
                        <div class="px-4 sm:p-0">
                            <div class="mt-6 w-full before">
                                <div class="before_container">
                                    <img :src="uploadedImageUrl" class="object-cover" alt="" />
                                    <div class="break-words pt-2 text-violet-100 text-base font-normal font-['Inter'] leading-tight" v-html="order.orderPattern.body"></div>
                                </div>
                            </div>
                        </div>
                        <button @click.prevent="sendPostByBot" class="py-2 my-2 rounded-3xl w-full bg-purple-600 transition hover:bg-purple-800 text-violet-100 text-base font-bold font-['Open Sans'] leading-snug">Отправить пост через бота</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped lang="scss">
main{
    width: 100%;
    border-radius: 30px 30px;
    border: 1px solid rgba(101, 34, 217, 1);
    box-shadow: 0 3px 40px 0 rgba(41, 37, 0, 0.10);
}
.top{
    padding: 65px;
    border-radius: 30px;
    background: rgba(7, 12, 41, 1);

    @media screen and (max-width: 640px){
        padding: 10px;
        height: 70vh;
    }
    .overflowing{
        overflow-y: auto;
        height: calc(100% - 35px);
        @media screen and (max-width: 640px){
            height: calc(100% - 75px);
        }
    }
    .grid{
        grid-template-columns: minmax(0, 8fr) minmax(0, 4fr);
    }
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
.before{
    padding: 50px 40px;
    background: url('/images/background.jpg') center;
    background-size: cover;
    border-radius: 20px;
    .before_container{
        border-radius: 20px;
        background: #176073;
        padding: 20px;
    }
}
</style>
