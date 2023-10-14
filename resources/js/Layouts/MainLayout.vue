<script setup>

import Header from "@/Components/Header.vue";
import {container} from "jenesius-vue-modal";
import Footer from "@/Components/Home/Footer.vue";
</script>
<template>
    <Header/>
    <slot/>
    <Footer/>
    <container/>

</template>
