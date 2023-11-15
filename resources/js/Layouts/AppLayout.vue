<script setup>
import {Head} from '@inertiajs/vue3';
import Header from "@/Components/Dashboard/Header.vue";
import {container} from "jenesius-vue-modal";
import Footer from "@/Components/Home/Footer.vue";
import MobileNav from "@/Components/Dashboard/MobileNav.vue";
defineProps({
    title: String,
});
const width = window.innerWidth
</script>

<template>
    <div>
        <Head :title="title"/>
        <Header/>
        <div class="container mx-auto">
            <slot/>
            <div v-show="width <= 640">

            </div>
        </div>
        <MobileNav/>
        <Footer/>
        <container/>
    </div>
</template>
