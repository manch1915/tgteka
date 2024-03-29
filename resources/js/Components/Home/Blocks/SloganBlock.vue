<script setup>
import { openRegister } from "@/utilities/authModals.js";

const props = defineProps({
    header: String,
    paragraph: String,
});
</script>
<template>
    <main class="main main-padding min-h-[600px]">
        <div class="icon-container">
            <img src="/images/bgcomputer.svg" alt="Icon 1" class="icon icon1" />
            <img src="/images/bgmessange.svg" alt="Icon 1" class="icon icon2" />
            <img
                src="/images/bgnotification.svg"
                alt="Icon 1"
                class="icon icon3"
            />
            <img
                src="/images/bgplane.svg"
                alt="Icon 1"
                class="sm:block hidden icon icon4"
            />
            <img
                src="/images/bgplane-mobile.svg"
                alt="Icon 1"
                class="sm:hidden icon icon4"
            />
            <img src="/images/bgtelegram.svg" alt="Icon 1" class="icon icon5" />
            <img src="/images/bgwindow.svg" alt="Icon 1" class="icon icon6" />
        </div>
        <div class="container mx-auto main__block">
            <div
                class="flex flex-col items-center justify-center gap-6 sm:gap-10 px-4"
            >
                <div>
                    <div
                        class="drop-shadow-md sm:text-4xl lg:text-5xl text-2xl sm:p-0 font-bold text-paleblue text-center"
                    >
                        {{ props.header }}
                    </div>
                </div>
                <p class="main_paragraph text-lg text-paleblue text-center">
                    {{ props.paragraph }}
                </p>
                <button
                    @click.prevent="openRegister()"
                    class="main_button sm:text-lg text-base font-bold text-paleblue text-center"
                >
                    Купить размещение
                </button>
            </div>
        </div>
        <a href="!#" class="arrow-main-down"></a>
        <div class="main__fon_blur main__fon_blur--left"></div>
        <div class="main__fon_blur main__fon_blur--right"></div>
    </main>
</template>

<style scoped lang="scss">
.main {
    display: flex;
    position: relative;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    height: 100%;
    z-index: 2;
    overflow: hidden;

    .main__block {
        z-index: 9;
        max-width: 60rem;
        .main_paragraph {
            max-width: 27rem;
            font-size: 20px;
            line-height: 1.3;
        }
        .main_button {
            transition: all 0.5s ease;
            line-height: 1.2;
            &:hover {
                box-shadow: 0 0 17px 0 #7375ae;
            }
        }
    }
    .arrow-main-down {
        display: block;
        width: 60px;
        height: 60px;
        position: absolute;
        bottom: 5%;
        left: 50%;
        background-image: url("/images/uim_arrow-circle-down.svg");
        transform: translateX(-50%);
    }
    & .icon-container {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        overflow: hidden;
    }

    & .icon {
        position: absolute;
        /* Common icon styles (width, height, etc.) go here */
        max-width: 100px; /* Or whatever size you want */
        max-height: 100px; /* Or whatever size you want */
        width: 100%; /* Will resize down to the max-width */
        height: auto; /* Will resize down to the max-height */
    }

    & .icon1 {
        width: 88px;
        height: 88px;
        top: 75%;
        left: 75%;

        @media screen and (max-width: 640px) {
            width: 30px;
            height: 30px;
        }
    }
    & .icon2 {
        width: 40px;
        height: 40px;
        top: 57%;
        left: 32%;

        @media screen and (max-width: 640px) {
            width: 20px;
            height: 20px;
        }
    }
    & .icon3 {
        width: 47px;
        height: 47px;
        top: 40%;
        left: 80%;

        @media screen and (max-width: 640px) {
            width: 23px;
            height: 23px;
        }
    }
    & .icon4 {
        max-width: 420px;
        max-height: 195px;
        top: 22%;
        left: 60%;

        @media screen and (max-width: 640px) {
            top: 0%;
            left: 50%;
            transform: translateX(-50%);
            max-width: 320px;
            max-height: 223px;
        }
    }
    & .icon5 {
        width: 85px;
        height: 85px;
        top: 35%;
        left: 20%;

        @media screen and (max-width: 640px) {
            width: 30px;
            height: 30px;
        }
    }
    & .icon6 {
        width: 85px;
        height: 85px;
        top: 70%;
        left: 20%;

        @media screen and (max-width: 640px) {
            width: 30px;
            height: 30px;
        }
    }

    &::before {
        content: "";
        position: absolute;
        // top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url("/images/background.svg");
        background-repeat: repeat-x;
        background-position: bottom center;
        background-size: contain;
        height: 30%;
        width: 100%;

        z-index: 0;

        @media screen and (max-width: 640px) {
            // background: url("/images/background.svg") no-repeat bottom center;
            // background-size: 200%;
        }
    }
    &__fon_blur {
        content: "";
        position: absolute;

        bottom: -20%;
        background-position: bottom center;
        position: absolute;
        height: 40%;
        width: 40%;
        background: radial-gradient(
            circle,
            rgba(214, 126, 226, 0.5) 0%,
            rgba(115, 54, 215, 0.5) 33%,
            rgba(0, 0, 0, 0.13) 89%,
            rgba(255, 255, 255, 0) 100%
        );
        z-index: -1;
        filter: blur(100px);
        @media (max-width: 640px) {
            filter: blur(50px);
        }
        &--left {
            left: 10%;
            @media (max-width: 640px) {
                left: 0%;
            }
        }
        &--right {
            right: 10%;
            @media (max-width: 640px) {
                right: 0%;
            }
        }
    }
    &::after {
    }
}

.main-padding {
    height: calc(100vh - 100px);
}

.main_button {
    padding: 16px 25px;
    border-radius: 100px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: #171961;
    box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25) inset;
}
</style>
