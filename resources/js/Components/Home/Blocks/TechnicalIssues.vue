<script setup>
import CallbackForm from "@/Components/Home/CallbackForm.vue";
</script>

<template>
    <div class="clients_mid">
        <div class="curvedside">
            <div class="curvedside__mask"></div>
            <div class="edge"></div>
            <div class="curvedside__base">
                <div class="curvedside__content">
                    <div class="clients clients_mid">
                        <div
                            class="issues flex flex-col items-center justify-center sm:pb-20 p-0 fix_line"
                        >
                            <div
                                class="text-center sm:pb-14 pb-4 px-4 text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-violet-600 sm:text-5xl text-3xl font-bold font-['Open Sans']"
                            >
                                Возникли технические вопросы: <br />
                                проблемы с доступом или оплатой?
                            </div>
                            <div
                                class="text-center text-violet-100 px-4 text-base font-bold font-['Open Sans'] leading-tight"
                            >
                                Заполните форму и мы с вами свяжемся
                            </div>
                            <CallbackForm />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
$bg: linear-gradient(160deg, rgba(55, 50, 189, 1) 11%, rgba(7, 12, 41, 1) 60%);
$mask: "/images/background-Vector-1.svg";
$maskHeight: 20vw; /* адаптив */
$maskPos: "top";
$minHeight: 300px;
$offset: 0px;
.clients_mid {
    height: 100%;

    .curvedside {
        position: relative;
        width: 100%;
        z-index: 2;

        .curvedside__mask {
            position: absolute;
            pointer-events: none;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            mask-image: url($mask);
            mask-repeat: no-repeat;
            mask-position: #{$maskPos} left;
            z-index: -1;
            mask-size: 100%, $maskHeight, contain;
            /*mask-repeat: repeat-x*/

            background: $bg;
            @media (max-width: 640px) {
                mask-size: 100%, 25vw, cover;
                width: 1000px;
            }
        }

        .curvedside__base {
            min-height: calc($minHeight + $offset);
            width: 100%;
            margin-top: 50px;

            background: $bg;

            background-clip: content-box;
            padding-#{$maskPos}: $maskHeight;
            @media (max-width: 640px) {
                background: transparent;
                padding-#{$maskPos}: 25vw;
            }
        }

        .curvedside__content {
            min-height: 300px;
            position: relative;

            margin-top: $offset;
        }

        .edge {
            position: absolute;
            pointer-events: none;
            top: 0;
            left: 0;
            width: 100%;
            height: $maskHeight;
            background-image: url("/images/vector-volna.svg");
            background-size: contain;
            background-repeat: no-repeat;
            @media (max-width: 640px) {
                width: 1000px;
                height: 27vw;
                background-size: cover;
            }
        }
    }
	
	.fix_line {
		border-bottom: 1px solid #070c29;
	}
}
</style>
