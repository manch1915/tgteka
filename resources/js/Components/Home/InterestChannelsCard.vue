<script setup>

</script>

<template>
    <div class="category rounded md:rounded-xl lg:rounded-2xl xl:rounded-3xl self-start">
        <div class="text-center px-3 py-3 sm:px-4 sm:py-4 md:px-5 md:py-5 lg:px-6 lg:py-6 xl:px-10 xl:py-11 text-violet-100 lg:text-base xl:text-lg font-bold font-['Open Sans'] leading-normal">Криптовалюта <br/>и Блокчейн</div>
    </div>
</template>

<style lang="scss" scoped>
.category {
    position: relative;
    display: block;
    background: linear-gradient(45deg, #24247A, #535393);
    backdrop-filter: blur(21px);

    &:before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 4px;
        background:linear-gradient(-130deg,rgba(89,61,239,1), rgba(21,21,21,0));
        -webkit-mask:
            linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
    }
}

</style>
