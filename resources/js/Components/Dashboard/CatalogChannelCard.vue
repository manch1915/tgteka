<script setup>
import {NSelect,} from "naive-ui";
import {selectCatalogThemeOverrides} from "@/themeOverrides.js";
import {ref} from "vue";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiHeartOutline,mdiCartPlus} from '@mdi/js';
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    channel: Object
})
const formatValue = ref(1)
const countValue = ref(1)
const format = [
    {
        label: '1/24',
        value: 1,
    },
    {
        label: '2/48',
        value: 2,
    },
    {
        label: '3/72',
        value: 3,
    },
    {
        label: '3/без удаления',
        value: 4,
    }
]
const count = [
    {
        label: '1',
        value: 1,
    },
    {
        label: '2',
        value: 2,
    },
    {
        label: '3',
        value: 3,
    },
    {
        label: '4',
        value: 4,
    }
]

const wrap = ref(false)

</script>

<template>
    <div class="channel_card">
        <div class="channel_card-container">
            <div class="grid">
                <div class="flex grid-element flex-col items-center justify-center gap-y-3">
                    <div class="avatar">
                        <img src="/images/avatar.jpg" alt="avatar">
                    </div>
                </div>
                <div class="grid-element">
                    <div class="flex flex-col gap-y-2 justify-between">
                        <h1 class="text-white text-xl font-bold font-['Open Sans'] leading-relaxed">{{channel.channel_name}}</h1>
                        <p class="text-white box-content line-clamp-3  text-sm font-normal font-['Poppins'] break-all leading-tight">{{channel.description}}</p>
                    </div>
                </div>
                <div class="grid-element flex flex-col items-center justify-center">
                    <div class="flex h-full flex-col items-center justify-around text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Подписчики</p>
                        <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">146 774</p>
                    </div>
                </div>
                <div class="grid-element flex flex-col items-center justify-center">
                    <div class="flex h-full flex-col items-center justify-around text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Просмотры</p>
                        <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">36.7К</p>
                    </div>
                </div>
                <div class="grid-element flex flex-col items-center justify-center">
                    <div class="flex h-full flex-col items-center justify-around text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                        <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Подписчики</p>
                        <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">CPМ</p>
                    </div>
                </div>
            </div>
        </div>
            <div class="flex items-center justify-between p-6 unwrap">
                <div class="flex w-full items-center gap-x-4">
                    <div class="flex flex-col gap-y-1 items-start w-1/6">
                        <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Формат</p>
                        <n-select v-model:value="formatValue" :theme-overrides="selectCatalogThemeOverrides" placeholder="" :options="format"/>
                    </div>
                    <div class="flex flex-col gap-y-1 items-start w-1/6">
                        <p class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Количество</p>
                        <n-select v-model:value="countValue" :theme-overrides="selectCatalogThemeOverrides" placeholder="" :options="count"/>
                    </div>
                    <h1 class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">18 000 ₽</h1>
                </div>
                <div class="flex justify-between gap-x-2.5 items-center text-violet-100">
                    <Link :href="route('catalog.channels.show', channel.id )" class="text-violet-100 text-xs font-normal font-['Open Sans'] underline leading-none">Подробнее о канале</Link>
                    <div class="pl-2">
                        <BaseIcon size="25" fill="#ffffff" :path="mdiHeartOutline"/>
                    </div>
                    <div>
                        <BaseIcon size="25" fill="#ffffff" :path="mdiCartPlus"/>
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
    .grid{
        grid-template-columns: 1fr 5fr 2fr 2fr 2fr;

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
