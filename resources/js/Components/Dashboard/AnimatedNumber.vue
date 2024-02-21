<template>
    {{ displayNumber }}
</template>

<script setup>
import {ref, watch, onBeforeUnmount} from 'vue';

let displayNumber = ref(0);
let interval = null;

const props = defineProps({
    number: {
        type: Number,
        default: 0
    }
});

// Check if the animation has already played in this session
const hasAnimationPlayed = sessionStorage.getItem('animatedNumberPlayed');

if (!hasAnimationPlayed) {
    watch(() => props.number, (newVal, oldVal) => {
        clearInterval(interval);

        if (newVal === oldVal) return;

        interval = setInterval(() => {
            if (displayNumber.value !== newVal) {
                let change = (newVal - displayNumber.value) / 10;
                change = change >= 0 ? Math.ceil(change) : Math.floor(change);

                displayNumber.value += change;
            } else {
                clearInterval(interval);

                // Ensure the final value is correct
                displayNumber.value = newVal;

                // Set the flag to indicate that the animation has played in this session
                sessionStorage.setItem('animatedNumberPlayed', 'true');
            }

        }, 20);
    }, {immediate: true});
} else {
    // Immediately set the value to the desired number if the animation has already played in the session
    displayNumber.value = props.number;
}

// Clear the interval when the component is unmounted
onBeforeUnmount(() => {
    clearInterval(interval);
});
</script>
