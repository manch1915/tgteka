<script setup></script>

<template>
    <div class="integration relative">
        <div class="fon-radial-purple"></div>
        <div class="fon-radial-blue"></div>
        <div class="container mx-auto">
            <div
                class="integration__header text-center sm:mt-32 mt-14 mb-24 py-12 px-4"
            >
                <span
                    class="text-violet-100 lg:text-4xl text-3xl font-bold font-['Open Sans'] leading-10"
                    >Как купить интеграцию в Телеграм с&nbsp;TGteka.ru</span
                >
            </div>
            <div
                class="fon-radial grid lg:grid-cols-2 grid-cols-1 lg:gap-0 gap-y-8 justify-center items-center justify-items-center"
            >
                <slot name="comparePairs"> </slot>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.integration__header {
    position: relative;

    &:before {
        content: "";
        position: absolute;
        left: 0;
        top: 38%;
        width: 100%;
        height: 100%;
        background: url("/images/planeInter.svg") no-repeat center;
        background-size: contain;
    }
}
.fon-radial {
    position: relative;
    &:before {
        content: "";
        position: absolute;

        display: block;
        left: 50%;
        transform: translate(-50%, 0);
        bottom: 0;
        height: 100%;
        width: 100vw;
        z-index: 0;
        background: radial-gradient(
            50% 50% at 50% 50%,
            rgba(174, 126, 255, 0.3) 0%,
            rgba(174, 126, 255, 0) 100%
        );

        background-repeat: no-repeat;
        background-position: center;
        filter: blur(100px);
    }
}
.fon-radial-purple,
.fon-radial-blue {
    position: absolute;

    display: block;
    left: 0;
    bottom: -25%;
    height: 700px;
    width: 700px;
    z-index: 0;
    background: radial-gradient(
        50% 50% at 50% 50%,
        rgba(141, 112, 254, 1) 0%,
        rgba(80, 55, 173, 0) 100%
    );
    background-size: 100% 100%;
    background-repeat: no-repeat;
    background-position: center;
    filter: blur(200px);
    z-index: 0;
    @media (max-width: 1024px) {
        bottom: -10%;
    }
}
.fon-radial-blue {
    left: auto;
    right: 0;
    top: 0;
    @media (max-width: 1024px) {
        right: 25%;
        top: 25%;
    }
}

.icons {
}
</style>
