<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    title: String,
});


const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title" />

      <slot></slot>


    </div>
</template>
