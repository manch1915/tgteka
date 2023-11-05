<script setup>

</script>
<template>
    <div class="clients overflow-hidden">
        <div class="container mx-auto">
            <slot name="clients-header"></slot>
            <div class="pt-32">
                <slot name="slider">

                </slot>
            </div>
            <slot name="clients-footer">

            </slot>
        </div>
    </div>
</template>

<style scoped lang="scss">

.clients {
    background: url("/images/interestChannelsBackground.svg") no-repeat center;
    background-position-y: 50%;
    background-size: cover;
    padding-top: 400px;
    @media screen and (max-width: 640px) {
        background-position-y: -280px;
    }
}

</style>
