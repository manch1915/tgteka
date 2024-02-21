<script setup>
import {closeModal, pushModal} from "jenesius-vue-modal";
import {mdiCheck, mdiClose, mdiContentCopy, mdiEyeOutline, mdiForumOutline} from "@mdi/js";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {inputThemeOverrides} from "@/themeOverrides.js";
import {NButton, NImage, NImageGroup, NInput, NInputGroup, useMessage} from "naive-ui";
import {onMounted, ref} from "vue";
import CancelOrder from "@/Components/Dashboard/CancelOrder.vue";
import OrderCard from "@/Components/Dashboard/OrderCard.vue";
import MainVideoPlayer from "@/Components/Dashboard/MainVideoPlayer.vue";

const props = defineProps({
    order: Object
})
const listOfLinks = ref([]);
onMounted(() => {
    parseLinks()
})
const parseLinks = () => {
    const parser = new DOMParser();
    const dom = parser.parseFromString(props.order.orderPattern.body, 'text/html');
    const links = Array.from(dom.links);

    links.forEach(link => {
        listOfLinks.value.push({
            href: link.getAttribute('href'),
            text: link.innerText,
        });
    });
}

const uploadedImageUrl = ref(props.order.orderPattern.patternMedia || '/images/photo.png');

const message = useMessage()

const decline = () => {
    pushModal(CancelOrder, {order: props.order.id})
}

const sendPostByBot = () => {
    axios.post(route('order.send-pattern-by-bot'), {pattern: props.order.orderPattern})
        .catch(error => message.error(error.response.data.message))
}
const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text)
        .then(() => {
            message.success('Link copied to clipboard');
        })
        .catch(err => {
            console.error('Could not copy text: ', err);
        });
}

const openVideoPlayer = function(data) {
    pushModal(MainVideoPlayer, {url: data.url, image: data.image})
};
</script>

<template>
    <div class="mission-scrollbar container overflow-y-auto h-full">
        <main>
            <div class="top">
                <div class="header">
                    <div class="float-right cursor-pointer" @click.prevent="closeModal()">
                        <img src="/images/Icon-close.svg" alt="">
                    </div>
                    <div class="flex flex-col items-start justify-center gap-y-6 sm:items-center mb-8">
                        <h1 class="sm:text-center text-start text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10">Посмотреть задание</h1>
                    </div>
                </div>
                <order-card :order="order" :is-card="false"/>
                <div class="mt-8 grid gap-x-4">
                    <div>
                        <div class="flex justify-between py-4">
                            <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Срок публикации: <span class="text-opacity-60 text-sm font-normal font-['Open Sans'] leading-tight">В ближайшее время</span></p>
                            <h1 class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Время публикации: --</h1>
                        </div>
                        <div class="flex justify-between border-y-2 border-violet-100 border-opacity-40 py-4">
                            <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Формат размещения <span class="text-opacity-60 text-sm font-normal font-['Open Sans'] leading-tight">{{order.format.name}}</span></p>
                        </div>
                        <template v-for="list in listOfLinks">
                            <div class="py-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Ссылка в тексте</div>
                                    <div class="text-violet-100 text-sm font-bold py-1 px-2 rounded-lg bg-slate-700 font-['Open Sans'] leading-tight">{{list.text}}</div>
                                </div>
                                <n-input-group class="flex items-center gap-x-2">
                                    <n-input class="my-1 py-1.5" readonly :value="list.href" placeholder="Ссылка в тексте" :theme-overrides="inputThemeOverrides"/>
                                    <n-button @click.prevent="copyToClipboard(list.href)">
                                        <template #icon>
                                            <BaseIcon :path="mdiContentCopy"/>
                                        </template>
                                    </n-button>
                                </n-input-group>
                            </div>
                        </template>
                    </div>
                    <div>
                        <div class="px-4 sm:p-0">
                            <div class="mt-6 w-full before">
                                <div class="before_container">
                                    <n-image-group v-if="order.orderPattern.patternMedia.length">
                                        <transition-group name="fade" tag="div" :class="[order.orderPattern.patternMedia.length <= 2 ? 'grid-cols-1' : '',
                                                                              order.orderPattern.patternMedia.length === 3 ? 'grid-cols-1 sm:grid-cols-2' : '',
                                                                              order.orderPattern.patternMedia.length >= 4 && order.orderPattern.patternMedia.length <= 6 ? 'grid-cols-2' : '',
                                                                              order.orderPattern.patternMedia.length > 6 ? 'grid-cols-3' : '']" class="grid gap-2">
                                            <div v-for="(image, index) in order.orderPattern.patternMedia" :key="'img-' + index" class="bg-center bg-cover w-full">
                                                <img class="cursor-pointer w-full object-fill" @click.prevent="openVideoPlayer(image)"  v-if="['.mp4', '.ogg', '.webm'].some(ext => image.url.includes(ext))" :src="image.thumbnail_path" alt=""/>
                                                <n-image v-else :src="image.url" alt="" class="w-full h-full" />
                                            </div>
                                        </transition-group>
                                    </n-image-group>
                                    <div v-else>
                                        <img :src="uploadedImageUrl" class="object-cover" alt="" />
                                    </div>
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
.mission-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgb(156, 163, 175) rgb(249, 250, 251);
}

.mission-scrollbar::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.mission-scrollbar::-webkit-scrollbar-track {
    @apply bg-gray-50;
}

.mission-scrollbar::-webkit-scrollbar-thumb {
    @apply bg-gray-400 rounded;
}

.mission-scrollbar::-webkit-scrollbar-thumb:hover {
    @apply bg-gray-500;
}
</style>
