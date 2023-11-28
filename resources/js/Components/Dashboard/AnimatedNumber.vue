<template>
    {{ displayNumber }}
</template>

<script setup>
import { ref, watch } from 'vue';

let displayNumber = ref(0);
let interval = null;

const props = defineProps({
    number: {
        type: Number,
        default: 0
    }
})

watch(() => props.number, (newVal, oldVal) => {

    clearInterval(interval);

    if (newVal === oldVal) return;

    interval = setInterval(() => {

        if (displayNumber.value !== newVal) {

            let change = (newVal - displayNumber.value) / 10;

            change = change >= 0 ? Math.ceil(change) : Math.floor(change);

            displayNumber.value += change;

        }

    }, 20);

}, { immediate: true });

</script>
