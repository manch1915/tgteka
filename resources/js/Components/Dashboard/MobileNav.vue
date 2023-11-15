<script setup>

import {Link} from "@inertiajs/vue3";
</script>

<template>
    <div class="nav-bar p-2">
        <main class="flex justify-evenly items-center w-full">
            <Link :href="route('personal-data')">
                <img src="/images/person.svg" alt="">
            </Link>
            <img src="/images/pattern.svg" alt="">
            <img src="/images/bell.svg" alt="">
            <img src="/images/messenger.svg" alt="">
        </main>
    </div>
</template>

<style scoped lang="scss">
.nav-bar{
    position:fixed;
    bottom:0;
    left:0;
    right:0;
    z-index:1000;

    will-change:transform;
    transform: translateZ(0);

    display:flex;

    height: 65px;

    background: #1C1D6D;
    main{
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.10);
        background: #171961;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
        img{
            width: 30px;
            height: 30px;
        }
    }
}
</style>
