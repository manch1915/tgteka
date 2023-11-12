<script setup>

import ReviewsCard from "@/Components/Dashboard/ChannelTab/ReviewsCard.vue";
import {NScrollbar} from "naive-ui";
import {scrollbarThemeOverrides} from "@/themeOverrides.js";
</script>

<template>
    <div class="w-full reviews p-6">
        <n-scrollbar :theme-overrides="scrollbarThemeOverrides" class="max-h-96 ">
            <div class="grid grid-cols-2 gap-5 px-1">
            <ReviewsCard/>
            <ReviewsCard/>
            <ReviewsCard/>
            <ReviewsCard/>
            <ReviewsCard/>
            <ReviewsCard/>
            <ReviewsCard/>
            <ReviewsCard/>
            </div>
        </n-scrollbar>
    </div>
</template>

<style scoped lang="scss">
.reviews{
    border-radius: 0px 30px 30px 30px;
    background: #5A5F77;
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);

}
</style>
