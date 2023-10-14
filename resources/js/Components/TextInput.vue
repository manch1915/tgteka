<script setup>
import {onMounted, ref} from 'vue';

defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <input
        ref="input"
        class="auth focus:border-transparent focus:ring-0 rounded-3xl"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
<style scoped lang="scss">
.auth {
    color: rgba(234, 224, 255, 0.4);
    background: rgba(13, 20, 58, 1);
    border: 1px solid rgba(101, 34, 217, 1);
}
</style>
