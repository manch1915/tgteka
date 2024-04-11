<template>
    <div class="sm:pt-48 pt-12 platform px-4">
        <div class="container mx-auto">
            <h1 class="text-center text-4xl font-bold text-paleblue">
                Как работает платформа?
            </h1>
            <div
                class="grid grid-cols-1 lg:gap-y-36 gap-y-12 gap-x-3 mt-24 points lg:grid-cols-3 relative lg:px-10 px-0"
            >
                <slot name="points" />
                <img
                    class="HowItWorksBlock_pointes pointes_linear lg:block hidden"
                    src="/images/points_3.svg"
                    alt=""
                />
                <img
                    class="notHowItWorksBlock_pointes pointes_linear lg:block hidden"
                    src="/images/points-svg.svg"
                    alt=""
                />
                <div class="samolet">
                    <img src="/images/samolet.svg" alt="" />
                </div>

                <img
                    class="pointes_linear lg:hidden"
                    src="/images/points-mobile-svg.svg"
                    alt=""
                />
            </div>
        </div>
    </div>
</template>
<style scoped lang="scss">
.points {
    position: relative;
}
.HowItWorksBlock {
    .HowItWorksBlock_pointes {
        display: block;
        &.pointes_linear {
            left: 17%;
            top: -15%;
            width: 80%;
            height: 100px;
            transform: scale(1);
            @media (max-width: 1023px) {
                display: none;
            }
        }
    }
    .samolet {
        right: 1%;
        top: -27%;
        bottom: auto;
        z-index: 10;
        @media (max-width: 1535px) {
            top: -23%;
        }
        @media (max-width: 1280px) {
            top: -20%;
        }
        @media (max-width: 1023px) {
            top: auto;
            bottom: -60px;
            left: 280px;
            right: auto;
        }
    }
    .notHowItWorksBlock_pointes {
        display: none;
    }
}
.HowItWorksBlock_pointes {
    display: none;
}
.pointes_linear {
    pointer-events: none;
    left: -2%;
    top: 2%;
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 0;

    @media (max-width: 1023px) {
        height: 105%;
        width: 300px;
        left: 2%;
        top: 1%;
        transform: scale(1);
    }
    // @media (max-width: 640px) {
    //     left: 12%;
    // }
}
.samolet {
    pointer-events: none;
    width: 87px;
    height: 87px;
    position: absolute;
    right: 0%;
    bottom: 5%;
    z-index: 10;

    @media (max-width: 1280px) {
        bottom: 6%;
        right: -1%;
    }
    @media (max-width: 1023px) {
        bottom: -110px;
        left: 280px;
        right: auto;
    }
}
</style>
