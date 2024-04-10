<script setup></script>

<template>
    <div class="body">
        <div class="body__head flex justify-start gap-6 items-center">
            <div class="body__profile">
                <img
                    src="https://via.placeholder.com/108x108"
                    alt=""
                    class="rounded-full"
                />
            </div>
            <div class="body__data">
                <div>
                    <div
                        class="pb-2.5 text-center text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"
                    >
                        Доктор Амина
                    </div>
                    <div
                        class="body__podp text-center text-violet-100 sm:text-sm text-xs font-normal font-['Open Sans'] leading-tight"
                    >
                        127 995 подписчиков
                    </div>
                    <div
                        class="mt-4 lg:px-11 px-4 py-1.5 rounded-3xl border border-violet-100 justify-start items-start gap-2.5 inline-flex"
                    >
                        <div
                            class="text-violet-100 sm:text-sm text-xs font-bold font-['Open Sans'] leading-tight"
                        >
                            1/24 — 12 500 руб.
                        </div>
                    </div>
                </div>
                <div></div>
            </div>
        </div>
        <div
            class="body__buttons grid grid-cols-2 lg:gap-x-7 gap-2 gap-y-3 mt-5"
        >
            <div class="body__button">
                <div class="text-center">
                    <span
                        class="text-violet-100 sm:text-sm text-xs font-bold font-['Open Sans'] leading-tight"
                        >CRM<br /></span
                    ><span
                        class="text-violet-100 sm:text-sm text-xs font-normal font-['Open Sans'] leading-tight"
                        >905 руб.</span
                    >
                </div>
            </div>
            <div class="body__button">
                <div class="text-center">
                    <span
                        class="text-violet-100 sm:text-sm text-xs font-bold font-['Open Sans'] leading-tight"
                        >Просмотров за 24 часа <br /></span
                    ><span
                        class="text-violet-100 sm:text-sm text-xs font-normal font-['Open Sans'] leading-tight"
                        >49 002</span
                    >
                </div>
            </div>
            <div class="body__button sm:col-auto col-span-2">
                <div class="text-center">
                    <span
                        class="text-violet-100 sm:text-sm text-xs font-bold font-['Open Sans'] leading-tight"
                        >Статистика<br
                    /></span>
                </div>
            </div>
        </div>
        <div class="order">
            <button
                class="order_el pl-6 pr-11 py-1.5 btn_gradient-purple transition hover:bg-purple-800 rounded-tl-3xl rounded-br-3xl text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight"
            >
                Заказать размещение
            </button>
        </div>
    </div>
</template>

<style scoped lang="scss">
.body {
    position: relative;
    display: block;
    width: 100%;
    padding: 20px 20px 35px 20px;
    border-radius: 0 40px 40px 40px;

    background: linear-gradient(
        0deg,
        rgba(255, 255, 255, 0.26) 0%,
        rgba(255, 255, 255, 0) 100%
    );

    backdrop-filter: blur(21px);
    box-sizing: border-box;
    overflow: hidden;
    &::before {
        pointer-events: none;
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1.5px;
        background: linear-gradient(
                0deg,
                rgba(24, 25, 94, 1) 0%,
                rgba(21, 21, 21, 0) 100%
            ),
            linear-gradient(
                45deg,
                rgba(78, 64, 97, 1) 0%,
                rgba(130, 120, 185, 1) 100%
            ),
            linear-gradient(
                0deg,
                rgba(255, 255, 255, 1) 0%,
                rgba(255, 255, 255, 0) 100%
            );
        -webkit-mask: linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        z-index: 1;
    }
    @media screen and (max-width: 1024px) {
        margin-top: 20px;
    }
    &__head {
        @media screen and (max-width: 360px) {
            flex-direction: column;
        }
    }
    &__profile {
        width: 108px;
        height: 108px;
    }
    &__data {
        margin: 0 auto;
    }
    &__podp {
        display: flex;
        align-items: center;
        gap: 8px;
        &:before {
            content: "";
            width: 24px;
            height: 24px;

            display: block;
            background-image: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11.5459 14.3642C10.2243 14.3642 8.93238 13.9723 7.83351 13.2381C6.73464 12.5038 5.87817 11.4602 5.37242 10.2392C4.86666 9.01821 4.73434 7.67466 4.99217 6.37845C5.25 5.08225 5.88641 3.89161 6.82092 2.95709C7.75544 2.02258 8.94608 1.38617 10.2423 1.12834C11.5385 0.870506 12.882 1.00283 14.103 1.50859C15.324 2.01434 16.3677 2.87081 17.1019 3.96968C17.8361 5.06855 18.228 6.36047 18.228 7.68207C18.226 9.45366 17.5214 11.1521 16.2687 12.4048C15.0159 13.6575 13.3175 14.3622 11.5459 14.3642Z' fill='url(%23paint0_linear_3050_1163)' /%3E%3Cpath d='M16.5861 12.0187C15.9675 12.7531 15.1959 13.3433 14.3253 13.7483C13.4547 14.1532 12.5061 14.363 11.5459 14.363C10.5857 14.363 9.63715 14.1532 8.76654 13.7483C7.89592 13.3433 7.1243 12.7531 6.50566 12.0187C5.13098 12.8709 3.99597 14.0591 3.20767 15.4714C2.41937 16.8836 2.00375 18.4734 2 20.0908C2 20.5971 2.20115 21.0827 2.55919 21.4407C2.91723 21.7988 3.40283 21.9999 3.90918 21.9999H19.1826C19.689 21.9999 20.1746 21.7988 20.5326 21.4407C20.8907 21.0827 21.0918 20.5971 21.0918 20.0908C21.0881 18.4734 20.6724 16.8836 19.8841 15.4714C19.0958 14.0591 17.9608 12.8709 16.5861 12.0187Z' fill='url(%23paint1_linear_3050_1163)' /%3E%3Cdefs%3E%3ClinearGradient id='paint0_linear_3050_1163' x1='11.5459' y1='0.999939' x2='11.5459' y2='14.3642' gradientUnits='userSpaceOnUse'%3E%3Cstop offset='0.451042' stop-color='%239863E0' /%3E%3Cstop offset='1' stop-color='%23F2EAFE' /%3E%3C/linearGradient%3E%3ClinearGradient id='paint1_linear_3050_1163' x1='11.5459' y1='12.0187' x2='11.5459' y2='21.9999' gradientUnits='userSpaceOnUse'%3E%3Cstop offset='0.451042' stop-color='%239A63E4' /%3E%3Cstop offset='1' stop-color='%23E5D6F9' /%3E%3C/linearGradient%3E%3C/defs%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 24px 24px;

            @media screen and (max-width: 640px) {
                width: 16px;
                height: 16px;
                background-size: 16px 16px;
            }
        }
    }
}

.body__buttons {
    @media screen and (max-width: 640px) {
        grid-template-columns: repeat(2, minmax(110px, auto));
    }
    .body__button {
        padding: 7px 12px;
        justify-content: center;
        align-items: center;
        line-height: 1.2;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.1);
        box-shadow: 0px 1px 0px 0px rgba(255, 255, 255, 0.08) inset,
            0px 4px 4px 0px rgba(0, 0, 0, 0.2);
        @media screen and (max-width: 640px) {
            padding: 6px;
        }
    }
}
.order {
    position: absolute;
    bottom: 3px;
    right: 5px;
    .order_el {
    }
}
</style>
