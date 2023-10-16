<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import Header from "@/Components/Dashboard/Header.vue";

defineProps({
    title: String,
});


const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title"/>
        <Header/>
        <div class="container mx-auto">
            <slot/>
        </div>

    </div>
</template>
