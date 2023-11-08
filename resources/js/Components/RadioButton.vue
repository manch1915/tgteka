<template>
    <div class="flex items-center gap-x-2.5">
        <div
            class="circle"
            :class="{ checked: isChecked }"
            @click="processClick"
        >
            <div v-show="isChecked" class="inner-circle"></div>
        </div>
        <p class="text-violet-100 text-sm font-normal font-['Inter']">{{value === 'channel' ? 'Канал' : 'Группу/ чат'}}</p>
    </div>
</template>

<script setup>
import {computed } from 'vue';

const props = defineProps({
    value: { type: [String, Number, Boolean], required: true },
    modelValue: { type: [String, Number, Boolean], default: null },
});

const emit = defineEmits(["clicked"]);

let isChecked = computed(() => props.modelValue === props.value);

const processClick = () => {
    emit("clicked", props.value);
};

</script>

<style scoped>
.circle {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    border: 1px solid rgba(234, 224, 255, 1);
    cursor: pointer;
    position: relative;
}


.inner-circle {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 70%;
    height: 70%;
    margin: auto;
    background-color: rgba(135, 41, 255, 1);
    border-radius: 50%;
}
</style>
