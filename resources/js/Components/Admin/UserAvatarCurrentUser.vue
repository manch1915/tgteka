<script setup>
import { useMainStore } from "@/stores/main";
import UserAvatar from "@/Components/Admin/UserAvatar.vue";
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

const userName = computed(() => usePage().props.auth.user.name)
</script>

<template>
    <UserAvatar :username="userName" api="initials">
    <slot />
  </UserAvatar>
</template>
