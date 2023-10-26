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
    <select
        ref="input"
        class="auth focus:border-transparent focus:ring-0 rounded-3xl"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
        <option value="" selected><slot/></option>
    </select>
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
