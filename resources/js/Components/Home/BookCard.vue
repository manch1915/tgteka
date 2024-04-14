<template>
    <div class="card">
        <div class="handbook__card">
            <div class="content flex flex-col justify-between gap-4 lg:gap-8">
                <div class="handbook__card__icon">
                    <img src="/images/virtual-reality-vr.svg" alt="" />
                </div>
                <div
                    class="text-violet-100 text-sm lg:text-xl line-1-3 font-bold font-['Open Sans']"
                >
                    Преимущество 1
                </div>
                <div
                    class="text-violet-100 text-sm lg:text-xl line-1-3 font-light font-['Open Sans']"
                >
                    Расскажите, что отличает ваши услуги от услуг в других
                    компаниях
                </div>
            </div>
        </div>
        <div class="handbook__card__second"></div>
    </div>
</template>

<script setup></script>

<style lang="scss" scoped>
.card {
    position: relative;
    width: 334px;
    @media screen and (max-width: 1024px) {
        margin: 0 auto;
    }
    @media screen and (max-width: 609px) {
        width: 280px;
    }
    .handbook__card {
        div {
            transition: all 0.8s cubic-bezier(0.075, 0.82, 0.165, 1) 0s;
        }
        position: relative;
        height: 439px;
        padding: 35px;
        @media screen and (max-width: 609px) {
            height: 380px;
        }
        border-radius: 0 74px 74px 74px;
        // border: 1px solid rgba(255, 255, 255, 0.2);

        backdrop-filter: blur(15px);
        background: linear-gradient(
            135deg,
            rgba(255, 255, 255, 0.3) 0%,
            rgba(81, 63, 255, 0.1) 50%,
            rgba(81, 63, 255, 0) 100%
        );

        z-index: 1;
        &:before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: inherit;
            inset: 0;
            padding: 2.5px;

            // background: radial-gradient(
            //         circle,
            //         rgba(21, 21, 21, 1) 0%,
            //         rgba(21, 21, 21, 0) 100%
            //     ),
            //     radial-gradient(
            //         circle,
            //         rgba(166, 105, 232, 1) 0%,
            //         rgba(166, 105, 232, 0) 100%
            //     ),
            //     radial-gradient(
            //         circle,
            //         rgba(255, 255, 255, 1) 0%,
            //         rgba(23, 43, 71, 1) 100%
            //     );
            background: radial-gradient(
                    circle,
                    rgb(21, 21, 21) 0%,
                    rgba(21, 21, 21, 0) 100%
                ),
                radial-gradient(
                    circle,
                    rgb(207 179 239) 0%,
                    rgba(166, 105, 232, 0) 100%
                ),
                radial-gradient(circle, rgb(49 32 102) 0%, rgb(23, 43, 71) 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
        &__icon {
            width: 40px;
            margin: 18px 9px 0 auto;
        }
    }
    .handbook__card__second {
        transition: all 0.8s cubic-bezier(0.075, 0.82, 0.165, 1) 0s;
        position: absolute;
        height: 90%;
        width: 102%;
        border-radius: 0px 74px 74px 74px;

        backdrop-filter: blur(15px);

        transform: rotate(11deg);
        bottom: -20px;
        left: 8%;
        background: linear-gradient(
            135deg,
            rgb(95 85 131) 0%,
            rgb(40 34 52 / 0%) 100%
        );
        // background: linear-gradient(
        //     135deg,
        //     rgba(255, 255, 255, 0.4) 0%,
        //     rgba(255, 255, 255, 0) 100%
        // );
        overflow: hidden;
        &:before {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 0 74px 74px 74px;
            inset: 0;
            left: 0%;
            top: 0%;
            padding: 2.5px;
            // background: linear-gradient(
            //     135deg,
            //     rgba(129, 99, 192, 1) 0%,
            //     rgba(129, 99, 192, 0) 100%
            // );
            background: linear-gradient(
                135deg,
                rgb(59 43 92) 0%,
                rgba(129, 99, 192, 0) 100%
            );
            -webkit-mask: linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
    }
    &:hover {
        .handbook__card__second {
            transform: rotate(13deg);
        }
    }
}
</style>
