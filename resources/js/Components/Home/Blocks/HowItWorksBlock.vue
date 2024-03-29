<template>
    <div class="sm:pt-24 pt-12 platform px-3">
        <div class="container mx-auto">
            <h1 class="text-center text-4xl font-bold text-paleblue">
                Как работает платформа?
            </h1>
            <div
                class="grid grid-cols-1 lg:gap-y-36 gap-y-12 mt-24 points lg:grid-cols-3 relative"
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
            @media (max-width: 1024px) {
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
        @media (max-width: 1024px) {
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
    left: -5%;
    top: 1%;
    width: 100%;
    height: 103%;
    position: absolute;
    z-index: 0;

    @media (max-width: 1024px) {
        height: 105%;
        width: 300px;
        left: 1%;
        top: 0;
    }
}
.samolet {
    width: 87px;
    height: 87px;
    position: absolute;
    right: 1%;
    bottom: 5.5%;
    z-index: 10;
    @media (max-width: 1280px) {
        bottom: -7%;
    }
    @media (max-width: 1024px) {
        bottom: -80px;
        left: 280px;
        right: auto;
    }
}
</style>
