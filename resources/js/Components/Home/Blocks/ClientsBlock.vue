<script setup></script>
<template>
    <div class="clients_mid">
        <div class="curvedside">
            <div class="curvedside__mask"></div>
            <div class="edge"></div>
            <div class="curvedside__base">
                <div class="curvedside__content">
                    <div class="clients clients_mid">
                        <div class="w-full pb-28 fix_line">
                            <div class="container mx-auto">
                                <slot name="clients-header"></slot>
                                <div>
                                    <slot name="slider"> </slot>
                                </div>

                                <slot name="clients-footer"> </slot>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
$breakpoints: (
    "1920": 20,
    "1600": 25,
    "1440": 28,
    "1366": 30,
    "1280": 32,
    "1024": 35,
    "960": 37,
    "800": 40,
    "768": 42,
    "600": 50,
    "480": 60,
    "425": 65,
    "375": 70,
    "360": 75,
    "320": 80,
);
$breakpoints_bottom: (
    "1880": 70,
    "1770": 80,
    "1670": 90,
    "1570": 95,
    "1480": 90,
    "1440": 90,
    "1366": 100,
    "1280": 120,
    "1160": 135,
    "1024": 155,
    "960": 170,
    "840": 190,
    "780": 210,
    "770": 230,
    "760": 250,
    "720": 270,
    "670": 290,
    "640": 200,
    "540": 210,
    "510": 220,
    "470": 230,
    "450": 240,
    "410": 250,
    "380": 270,
    "320": 290,
);
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
                mask-size: 100%, 25vw, contain;
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
                //background: transparent;
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
