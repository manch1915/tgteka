<script setup>
import {router} from "@inertiajs/vue3";
import {NBadge, NPopover, NTabPane, NTabs} from "naive-ui";
import AboutChannel from "@/Components/Dashboard/ChannelTab/AboutChannel.vue";
import {nTabThemeOverrides} from "@/themeOverrides.js";
import {ref} from "vue";
import Reviews from "@/Components/Dashboard/ChannelTab/Reviews.vue";
import Statistics from "@/Components/Dashboard/ChannelTab/Statistics.vue";

import {useChannelStore} from "@/stores/ChannelStore.js";

const props = defineProps({
    channel: Object
})
const channelStore = useChannelStore();
const wrap = ref(false)
const goToCatalog = (channelName) => {
    // Redirect to the catalog page and perform search
    router.visit(route('catalog.channels.index'), {
        onFinish: () => {
            channelStore.searchData = channelName;
            channelStore.fetchChannels();
        }
    });
};
</script>

<template>
    <div class="channel_card">
        <div class="channel_card-container">
            <div class="grid">
                <div class="flex grid-element flex-col items-center justify-center gap-y-3">
                    <n-popover trigger="hover">
                        <template #trigger>
                            <div :class="{
                                'bg-yellow-400 point': channel.status === 'pending' || channel.status === 'loading',
                                'bg-green-400 point': channel.status === 'accepted',
                                'bg-red-400 point': channel.status === 'declined'
                                }">
                            </div>
                        </template>
                        <span>{{ $t('messages.' + channel.status) }}</span>
                    </n-popover>
                    <div class="flex rating text-white text-sm font-normal gap-x-2 font-['Open Sans'] leading-tight">
                        <img src="/images/gavat.svg" alt="">
                        <p>{{channel.rating}}</p>
                    </div>
                    <div class="avatar">
                        <img :src="channel.avatar" alt="avatar">
                    </div>
                </div>
                <div class="grid-element">
                    <div class="flex flex-col gap-y-2 justify-between">
                        <div>
                        <p class="rate_catalog inline text-violet-100 text-sm font-normal font-['Open Sans']">#{{channel.id}} в каталоге</p>
                        </div>
                        <h1 class="text-white text-xl font-bold font-['Open Sans'] !leading-tight">{{channel.channel_name}}</h1>
                        <p class="text-white box-content line-clamp-3  text-sm font-normal font-['Open Sans'] break-all leading-tight">{{channel.description}}</p>
                    </div>
                </div>
                <div class="grid-element flex flex-col items-center justify-center">
                    <div class="flex flex-wrap gap-y-3 w-full justify-around text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        <button @click.prevent="goToCatalog(channel.channel_name)"><button :disabled="channel.status !== 'accepted'" class="watch flex items-center gap-x-1.5">Канал в каталоге <i class="block eye"></i></button></button>
                        <button @click.prevent="router.visit(route('channels.edit', channel.slug))" class="edit">Редактировать канал</button>
                        <n-badge :value="channel.pending_order_count" type="info" >
                            <button @click.prevent="router.visit(route('order.index'))" class="orders flex items-center text-violet-100 gap-x-1.5">К заявкам <i class="block inkarrow"></i></button>
                        </n-badge>
                    </div>
                </div>
            </div>
          <transition name="expand">
            <div v-show="wrap" class="wrap p-3">
                <div class="flex">
                <n-tabs :theme-overrides="nTabThemeOverrides" type="line" animated>
                    <n-tab-pane name="oasis" tab="О канале">
                      <template #tab>
                        <div class="flex flex-col items-center card-container">
                          <div class="card"></div>
                          О канале
                        </div>
                      </template>
                       <AboutChannel :channel="channel"/>
                    </n-tab-pane>
                    <n-tab-pane name="the beatles" tab="Отзывы">
                      <template #tab>
                        <div class="flex flex-col items-center message-2-container">
                          <div class="message-2"></div>
                          Отзывы
                        </div>
                      </template>
                        <Reviews :channel-id="channel.id"/>
                    </n-tab-pane>
                    <n-tab-pane name="jay chou" tab="Статистика">
                        <template #tab>
                            <div class="flex flex-col items-center noun-bar-container">
                            <div class="noun-bar"></div>
                            Статистика
                            </div>
                        </template>
                        <Statistics :channel_id="channel.id"/>
                    </n-tab-pane>
                </n-tabs>
                </div>
            </div>
          </transition>
        </div>

        <div class="flex justify-center py-6 unwrap">
            <button @click.prevent="wrap = !wrap" class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal flex">
                {{ wrap ? 'Свернуть' : 'Развернуть' }}
                <i class="transform transition-transform ease-in-out duration-500 block arrow-down" :class="{ 'rotate-180': wrap }"></i>
            </button>
        </div>
    </div>
</template>
է
<style scoped lang="scss">
.expand-leave-active,
.expand-enter-active {
    transition: max-height 0.3s ease-out, padding 0.3s ease-out;
    overflow: hidden;
}

.expand-enter-to,
.expand-leave-from {
    max-height: 1000px;
    padding: 15px;
}

.expand-enter-from,
.expand-leave-to {
    max-height: 0;
    padding: 0;
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
    .grid{

        .grid-element{
            min-width: 0;
            padding: 20px;
            &:not(:last-child) {
                border-right: 1px solid #6522D9;
                @media screen and (max-width: 1024px){
                    border: none;
                }
            }
        }
        grid-template-columns: 2fr 3fr 6fr;
        @media screen and (max-width: 1024px){
            grid-template-columns: 1fr;
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
.watch:disabled {
    /* styles for disabled button */
    background-color: rgba(111, 111, 111, 0.45);
    cursor: not-allowed;
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
