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
    <textarea
        ref="input"
        rows="8"
        class="auth focus:border-transparent focus:ring-0 rounded-3xl resize-none overflow-y-hidden"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
    </textarea>
</template>
<style scoped lang="scss">
.auth {
    color: #EAE0FF;
    background: rgba(13, 20, 58, 1);
    border: 1px solid rgba(101, 34, 217, 1);
    &::placeholder{
        color: rgba(234, 224, 255, 0.4);

    }
}
</style>
