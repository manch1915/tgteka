<script setup>
import {openLogin} from "@/utilities/authModals.js";

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
    background: {
        type: Number,
        default: 1,
    },
});
</script>

<template class="relative">
    <div class="samo border__fon">
        <div class="sm:p-10 p-6">
            <div class="flex lg:gap-4 gap-x-4 gap-1 icons sm:pb-5 pb-9">
                <div
                    v-for="i in props.item.icons"
                    :class="' icons_el icons_el-' + i.class"
                >
                    <div
                        class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight"
                    >
                        {{ i.text }}
                    </div>
                </div>
            </div>
            <h3
                class="text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] sm:leading-10 leading-normal"
            >
                {{ props.item.header }}
            </h3>
            <ul
                :class="{ 'pb-36': !props.item.hasButton }"
                class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-[1.7rem] gap-4 sm:pt-5 pt-8 pl-5"
            >
                <li v-for="(text, index) in props.item.listText" :key="index">
                    {{ text }}
                </li>
            </ul>
            <div class="sm:pt-16 pt-7" v-if="props.item.hasButton">
                <button
                    class="rounded-full btn_gradient-purple px-6 py-4 text-base font-bold leading-normal text-violet-100 transition hover:bg-purple-800"
                    @click.prevent="openLogin"
                >
                    Создать проект
                </button>
            </div>
            <div
                v-if="props.item.showSaveTimeMoney"
                class="flex justify-end xl:pt-8 pt-9 pb-36"
            >
                <div>
                    <span class="text-2xl font-bold text-violet-100">x</span>
                    <span class="text-4xl font-bold text-violet-100">2</span>
                    <span class="text-2xl font-bold text-violet-100">
                        сэкономить <br />время и деньги</span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.samo {
    min-height: 100%;
    background: linear-gradient(
        45deg,
        rgba(255, 255, 255, 0.26) 0%,
        rgba(255, 255, 255, 0) 100%
    );
    overflow: hidden;
    display: flex;
    flex-direction: column;
    position: relative;
    border-radius: 0 40px 40px 40px;

    backdrop-filter: blur(10px);

    &::before {
        content: "";
        position: absolute;
        right: 0;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 208px;
        background-image: url("/images/cardlines.png");

        background-size: cover;
        background-repeat: no-repeat;
    }
    &:nth-child(1) {
        &::before {
            @media screen and (max-width: 1024px) {
                height: 150px;
            }
            @media screen and (max-width: 480px) {
                height: 78px;
            }
        }
        @media screen and (max-width: 1024px) {
            order: 1;
        }
    }
    &:nth-child(2) {
        background: linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.26) 0%,
                rgba(255, 255, 255, 0) 100%
            ),
            linear-gradient(
                180deg,
                rgba(48, 45, 168, 1) 0%,
                rgba(49, 44, 168, 0) 100%
            );
        &::before {
            height: 177px;
            background-image: url("/images/cardlines2.png");
            @media screen and (max-width: 1024px) {
                height: 155px;
            }
            @media screen and (max-width: 480px) {
                height: 70px;
            }
        }

    }
    &.border__fon {
        &:nth-child(1) {
            &:after {
                padding: 1.5px;
            }
        }
        &:after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 3.5px;
            background: rgb(24, 25, 94);
            pointer-events: none;
            background: linear-gradient(
                    45deg,
                    rgba(24, 25, 94, 1) 0%,
                    rgba(21, 21, 21, 0) 100%
                ),
                linear-gradient(
                    45deg,
                    rgba(89, 61, 239, 1) 0%,
                    rgba(89, 61, 239, 1) 100%
                ),
                linear-gradient(
                    45deg,
                    rgba(255, 255, 255, 1) 0%,
                    rgba(255, 255, 255, 0) 100%
                );
            -webkit-mask: linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
        }
    }
    ul {
        li {
            list-style: disc;
            &:not(:last-child) {
                margin-bottom: 32px;
                line-height: 1.2;
                @media screen and (max-width: 640px) {
                    margin-bottom: 22px;
                }
            }
        }
    }
}

.card_line {
    position: absolute;
    bottom: 0;
    object-fit: cover;
}

.icons {
    .icons_el {
        padding: 8px 12px;
        border-radius: 100px;
        @media screen and (max-width: 640px) {
            padding: 3px 9px;
        }
        .text-sm {
            @media screen and (max-width: 640px) {
                font-size: 12px;
            }
        }
    }
    .icons_el-white {
        div {
            color: #070c29 !important;
        }
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: var(--White, #eae0ff);
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
    }
    .icons_el-blue {
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: #171961;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
    }
}
</style>
