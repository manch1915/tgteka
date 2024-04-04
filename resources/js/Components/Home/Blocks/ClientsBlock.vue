<script setup></script>
<template>
    <div class="bg_fon clients clients_mid">
        <div class="w-full">
            <div class="container mx-auto">
                <slot name="clients-header"></slot>
                <div>
                    <slot name="slider"> </slot>
                </div>
                <slot name="clients-footer"> </slot>
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

.clients_mid {
    height: 100%;
    &.bg_fon {
    }
    // background: linear-gradient(
    //     to bottom,
    //     rgba(255, 255, 255, 0),
    //     rgba(255, 255, 255, 0.5),
    //     rgba(255, 255, 255, 0)
    // );
    // position: relative;
    // top: 0; /* Adjust this value to position the overlay */
    // left: 0;
    // width: 100%;
    // height: 100%;
}
</style>
