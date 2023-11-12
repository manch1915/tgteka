<script setup>
import TextInput from "@/Components/TextInput.vue";
import { ref } from "vue";

const emit = defineEmits(['search']);

const search = ref('');

const handleSearch = () => {
    emit('search', search.value);
}
</script>

<template>
    <div class="flex relative">
        <TextInput v-model="search" @input="handleSearch" class="w-full" placeholder="Поиск"/>
        <img class="cursor-pointer absolute search-channel-button" src="/images/search.svg" alt="search">
    </div>
</template>

<style scoped lang="scss">
.search-channel-button {
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    border-left: 1px solid #EAE0FF;
    padding-left: 10px;
}
</style>
