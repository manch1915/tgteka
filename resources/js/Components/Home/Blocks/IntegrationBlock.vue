<script setup></script>

<template>
    <div class="integration relative">
        <div class="fon-radial-purple"></div>
        <div class="fon-radial-blue"></div>
        <div class="container mx-auto">
            <div
                class="integration__header text-center lg:mt-32 mt-14 lg:mb-24 ml-auto mr-auto table pt-12 lg:pb-4 pb-12 px-4"
            >
                <span
                    class="text-violet-100 lg:text-4xl text-3xl font-bold font-['Open Sans'] leading-10"
                    >Как купить интеграцию в Телеграм с&nbsp;TGteka.ru</span
                >
            </div>
            <div
                class="fon-radial max-w-[1240px] mx-auto grid lg:grid-cols-2 grid-cols-1 lg:gap-x-10 xl:gap-x-24 px-4 gap-y-8 justify-center items-center justify-items-center"
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
        width: 120%;
        height: 100%;
        background: url("/images/planeInter.svg") no-repeat center;
        background-size: contain;
        @media (max-width: 1440px) {
            width: 105%;
        }
        @media (max-width: 1023px) {
            display: none;
        }
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
    pointer-events: none;
    display: block;
    left: 15%;
    bottom: 5%;
    height: 300px;
    width: 300px;
    z-index: 0;
    background: radial-gradient(
        50% 50% at 50% 50%,
        rgba(141, 112, 254, 1) 0%,
        rgba(80, 55, 173, 0) 100%
    );
    background-size: 100% 100%;
    background-repeat: no-repeat;
    background-position: center;
    filter: blur(100px);

    @media (max-width: 1024px) {
        bottom: 0%;
        left: auto;
        right: 0;
        height: 500px;
        width: 500px;
    }
}
.fon-radial-blue {
    left: auto;
    right: 10%;
    top: 15%;
    @media (max-width: 1024px) {
        right: auto;
        top: 25%;
        background: radial-gradient(
            50% 50% at 50% 50%,
            rgba(141, 112, 254, 1) 0%,
            rgba(80, 55, 173, 0) 100%
        );
    }
}

.icons {
}
</style>
