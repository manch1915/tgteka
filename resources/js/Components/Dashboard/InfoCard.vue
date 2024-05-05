<script setup>

</script>

<template>
    <div class="border_wrapper">
        <div class="flex flex-col justify-center gap-y-2 items-center">
            <slot/>
        </div>
    </div>
</template>

<style scoped lang="scss">
.border_wrapper{
    padding: 4px;
    background: linear-gradient(to bottom,  #2C10C3, #171961);
    border-radius: 30px;
    div{
        background: linear-gradient(to left bottom, #0A0F2C, #45495F);
        width: 160px;
        height: 120px;
        border-radius: 30px;
        @media screen and (max-width: 375px) {
            width: 140px;
            height: 100px;
        }
    }
}
</style>
