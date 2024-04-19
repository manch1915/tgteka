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
            <img src="/images/bgtelegram.svg" alt="Icon 1" class="icon icon5" />
            <img src="/images/bgwindow.svg" alt="Icon 1" class="icon icon6" />
        </div>
        <div class="container mx-auto main__block">
            <div
                class="flex flex-col items-center justify-center gap-6 sm:gap-10 px-4"
            >
                <div class="header">
                    <h1
                        class="drop-shadow-md sm:text-4xl lg:text-5xl text-2xl line-1-3 sm:p-0 font-bold text-paleblue text-center"
                    >
                        {{ props.header }}
                    </h1>
					<img
						src="/images/bgplane-mobile-cropped.svg"
						alt="Icon 1"
						class="sm:hidden"
					/>
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
        <a href="#" class="arrow-main-down"></a>
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


	.header__icon {

	}


	.header {
		position: relative;
		//padding-left: 30px;
		//padding-right: 30px;
		//max-width: 60%;

		h1 {
			//max-width: 80%;
			margin: 0 auto;
		}
		img {
			object-fit: cover;
			width: 90%;
			position: absolute;
			right: 0;
			top: 0;
			transform: translateY(-35%);
			//border: 1px solid #fff;
		}
	}

    .main__block {
        z-index: 9;
        max-width: 60rem;
        .main_paragraph {
            max-width: 27rem;
            font-size: 20px;
            font-weight: 300;
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
        pointer-events: none;
    }

    & .icon1 {
        width: 71px;
        height: 71px;
        top: 75%;
        left: 75%;
        opacity: 0.5;
        @media screen and (max-width: 640px) {
            width: 30px;
            height: 30px;
            display: none;
        }
    }
    & .icon2 {
        width: 40px;
        height: 40px;
        top: 57%;
        left: 32%;

        @media screen and (max-width: 640px) {
            width: 20px;
            left: 5%;
            top: auto;
            bottom: 5%;
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
            left: auto;
            top: auto;
            right: 5%;
            bottom: 18%;
        }
    }
    & .icon4 {
        max-width: 420px;
        max-height: 195px;
        top: 12%;
        left: 60%;

		@media screen and (max-width: 1023px) {
            right: 0px;
			left: initial;
        }

        @media screen and (max-width: 640px) {
            top: auto;
            bottom: 50vh;
            right: 0;
            //transform: translateX(-50%);
            width: 620px;
            //max-height: 223px;
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
            top: auto;
            bottom: 12%;
        }
    }
    & .icon6 {
        width: 48px;
        height: 48px;
        top: 70%;
        left: 20%;
        opacity: 0.5;
        @media screen and (max-width: 640px) {
            width: 30px;
            height: 30px;
            left: auto;
            top: auto;
            right: 7%;
            bottom: 8%;
        }
    }

    &::before {
        content: "";
        position: absolute;
        // top: 0;
        left: 0;
        right: 0;
        bottom: 0;

        background-image: url("/images/bg-cub-fon.svg");
        background-position: bottom center;
        background-repeat: repeat-x;
        background-size: contain;
        mask-image: url("/images/fon-tranparent.svg");
        mask-repeat: repeat-x;
        -webkit-mask-position: top left;
        mask-position: top left;
        z-index: -1;
        -webkit-mask-size: 100%, 10%, contain;
        mask-size: 100%, 10%, contain;

        filter: brightness(10);
        opacity: 0.7;
        height: 26%;
        width: 100%;
        z-index: 0;

        @media (max-width: 640px) {
            filter: brightness(4);
            height: 15%;
        }
    }
    &__fon_blur {
        content: "";
        position: absolute;
        background-position: bottom center;
        position: absolute;
		//opacity: 0.9;
        //  bottom: -50%;
        // height: 75%;
        // width: 40%;
        width: 300px;
        height: 200px;
		border-radius: 30%;
        bottom: -100px;
        background: #944EFE;
        z-index: -1;
        filter: blur(120px);
        @media (max-width: 640px) {
			width: 130px;
        	height: 130px;
			bottom: -100px;
            filter: blur(50px);
        }

        // @media (min-width: 1600px) {
        //     width: 635px;
        // }
        &--left {
            right: calc(50% + 70px);
			@media (max-width: 640px) {
				right: calc(50% + 40px);
			}
        }
        &--right {
            left: calc(50% + 70px);
			@media (max-width: 640px) {
				left: calc(50% + 40px);
			}
        }
    }
    &::after {
    }
}

.main-padding {
    height: calc(100vh - 100px);
    @media screen and (max-width: 767px) {
        height: calc(100vh - 60px);
    }
}

.main_button {
    padding: 16px 25px;
    border-radius: 100px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: #171961;
    box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25) inset;
}
</style>
