<template>
    <div>
        <RadioButton
            v-for="(value, index) in values"
            :key="index"
            :value="value"
            :modelValue="checkedValue"
            @clicked="radioButtonClicked"
        />
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import RadioButton from './RadioButton.vue';

const props = defineProps({
    modelValue: { type: [String, Number, Boolean], default: null },
    values: { type: Array, required: true },
});

const emit = defineEmits(["update:modelValue"]);

const checkedValue = ref(props.modelValue);
watch(() => props.modelValue, (newVal) => {
    checkedValue.value = newVal;
});

const radioButtonClicked = (val) => {
    checkedValue.value = val;
    emit('update:modelValue', val);
};
</script>

