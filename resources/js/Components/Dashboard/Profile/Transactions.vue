<script setup>
import TransactionsCard from "@/Components/Dashboard/Profile/TransactionsCard.vue";

const props = defineProps({
    transactions: Object
})
</script>

<template>
    <div class="flex flex-col gap-y-4 mt-12">
        <transactions-card v-for="transaction in transactions" :transaction="transaction"/>
    </div>
</template>

<style scoped lang="scss">

</style>
