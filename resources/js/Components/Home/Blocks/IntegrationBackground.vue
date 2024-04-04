<template>
    <div :class="classString">
        <img src="/images/integr-background.svg" alt="" />
    </div>
</template>
<script setup>
const props = defineProps({
    classString: {
        type: String,
        default: "",
    },
});
</script>
<style>
.margin-top {
    @media (max-width: 385px) {
        margin-top: 100px;
    }
}
</style>
