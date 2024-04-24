<script setup>
const props = defineProps({
    p: String,
});
</script>

<template>
    <div class="category br-40 self-start flex-col" style="width: 190px;">
        <p
            class="text-center px-3 py-3 sm:px-4 sm:py-4 md:px-5 md:py-5 lg:px-6 lg:py-6 xl:px-10 xl:py-10 text-violet-100 text-sm font-bold font-['Open Sans'] leading-normal"
        >
            {{ p }}
        </p>
    </div>
</template>

<style lang="scss" scoped>
.category {
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: linear-gradient(
        26deg,
        rgba(255, 255, 255, 0.26) 0%,
        rgba(255, 255, 255, 0) 100%
    );

    backdrop-filter: blur(21px);
    height: 115px;
    &:before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 3.5px;
        background: linear-gradient(
                7deg,
                rgba(24, 25, 94, 1) 0%,
                rgba(21, 21, 21, 0) 100%
            ),
            linear-gradient(45deg, rgba(89, 61, 239, 1), rgba(89, 61, 239, 1)),
            linear-gradient(
                0deg,
                rgba(255, 255, 255, 1) 0%,
                rgba(255, 255, 255, 0) 100%
            );
        -webkit-mask: linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
    }
}
</style>
