<template>
    <div :class="classString" class="pointer-events-none intergr">
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
<style scoped lang="scss">
.margin-top {
    pointer-events: none;
    @media (max-width: 895px) {
        margin-top: 80px;
        display: flex;
        justify-content: center;
        img {
            width: 900px;

            height: 155px;
            max-width: unset;
        }
    }
}
.intergr{
    img {
        @media screen and (min-width: 895px){
            width: 100%;
        }
    }
}

</style>
