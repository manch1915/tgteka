<script setup>

</script>
<template>
    <div class="clients_top"></div>
    <div class="clients clients_mid relative">
        <div class="absolute top-0 left-0 w-full">
        <div class="container mx-auto">
            <slot name="clients-header"></slot>
            <div class="pt-32">
                <slot name="slider">

                </slot>
            </div>
            <slot name="clients-footer">

            </slot>
        </div>
        </div>
    </div>
    <div class="clients_bottom"></div>
</template>

<style scoped lang="scss">
$breakpoints: (
    '1920': 20,
    '1600': 25,
    '1440': 28,
    '1366': 30,
    '1280': 32,
    '1024': 35,
    '960': 37,
    '800': 40,
    '768': 42,
    '600': 50,
    '480': 60,
    '425': 65,
    '375': 70,
    '360': 75,
    '320': 80
);
$breakpoints_bottom: (
    '1670': 90,
    '1570': 95,
    '1480': 100,
    '1440': 105,
    '1366': 110,
    '1280': 120,
    '1160': 135,
    '1024': 155,
    '960': 170,
    '840': 190,
    '780': 210,
    '770': 230,
    '760': 250,
    '720': 270,
    '670': 290,
    '640': 200,
    '540': 210,
    '510': 220,
    '470': 230,
    '450': 240,
    '410': 250,
    '380': 270,
    '320': 290,
);
.clients_top{
    background: url("/images/interestChannelsBackground.svg") no-repeat center;
    background-size: cover;
    background-position-y: calc(200px - 50vw);
    width: 100%;
    height: calc(#{map-get($breakpoints, '1440')}vw); // Initial height

    // Calculate height based on viewport width
    @each $bp, $height in $breakpoints {
        @media (max-width: #{$bp}px) {
            height: calc(#{$height}vw);
        }
    }
}
.clients_mid{
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0));
    position: relative;
    top: 0; /* Adjust this value to position the overlay */
    left: 0;
    width: 100%;
    height: calc(100% - 200px); /* Adjust this value to cover the gap between top and bottom */
}

.clients_mid::before,
.clients_mid::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 10px; /* Adjust this value to control the size of the glow */
    background: linear-gradient(45deg, #2f2ca4, #202075);
    filter: blur(13px); /* Apply a blur effect to enhance the glow */
}

.clients_mid::before {
    top: -10px;
}

.clients_mid::after {
    bottom: -10px;
}

.clients_bottom{
    background: url("/images/interestChannelsBackground.svg") no-repeat center;
    background-size: cover;
    background-position-y: calc(-137px - 50vw);
    width: 100%;
    height: calc(#{map-get($breakpoints_bottom, '1440')}vw); // Initial height

    // Calculate height based on viewport width
    @each $bp, $height in $breakpoints_bottom {
        @media (max-width: #{$bp}px) {
            height: calc(#{$height}vw);
        }
    }

}

</style>
