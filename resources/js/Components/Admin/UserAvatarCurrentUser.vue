<script setup>
import UserAvatar from "@/Components/Admin/UserAvatar.vue";
import {computed} from "vue";
import {usePage} from "@inertiajs/vue3";

const page = usePage()

const userName = computed(() => page.props.auth.user.name)
const userEmail = computed(() => page.props.auth.user.email)
const displayName = computed(() => userName.value ? userName.value : userEmail.value)
</script>

<template>
    <UserAvatar :username="displayName" api="initials">
    <slot />
  </UserAvatar>
</template>
