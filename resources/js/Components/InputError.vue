<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm leading-4" style="color: #ff8888;">
            {{ message }}
        </p>
    </div>
</template>
