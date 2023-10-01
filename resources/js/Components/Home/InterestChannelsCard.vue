<script setup>

</script>

<template>
    <div class="category">
        <div class="text-center text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Криптовалюта <br/>и Блокчейн</div>
    </div>
</template>

<style lang="scss" scoped>
.category {
    position: relative;
    display: inline-block;
    padding: 44px 40px;
    border-radius: 40px;
    background: linear-gradient(45deg, #24247A, #535393);
    backdrop-filter: blur(21px);

    &:before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 40px;
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
