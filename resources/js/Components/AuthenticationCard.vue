<template>
    <div class="wrapper sm:w-[600px] w-[95%]">
        <div class="auth w-full flex flex-col sm:justify-center items-center p-12 sm:px-16 px-4">
            <div class="flex justify-end w-full">
                <BaseIcon size="30" class="text-white cursor-pointer" :path="mdiClose" @click.prevent="closeModal()"/>
            </div>
            <div>
                <div class="text-center text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10"><slot name="logo" /></div>
            </div>

            <div class="w-full">
                <slot/>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.wrapper{
    position: relative;
    &::before {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        border-radius: 0 40px 40px 40px;
        background: radial-gradient(
                278.82% 137.51% at 1.95% 3.59%,
                rgba(255, 255, 255, 0.26) 0%,
                rgba(255, 255, 255, 0) 100%
        );
        backdrop-filter: blur(21px);
        transform: rotate(10deg);
        z-index: -1;
    }
}
.auth {
    border-radius: 0 40px 40px 40px;
    background: radial-gradient(
            278.82% 137.51% at 1.95% 3.59%,
            rgba(255, 255, 255, 0.26) 0%,
            rgba(255, 255, 255, 0) 100%
    );
    backdrop-filter: blur(7px);
    z-index: 2;
}

</style>
<script setup>
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiClose} from "@mdi/js";
import {closeModal} from "jenesius-vue-modal";
</script>
