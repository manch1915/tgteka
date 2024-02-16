<script setup>
import ReviewsCard from "@/Components/Dashboard/ChannelTab/ReviewsCard.vue";
import {NScrollbar} from "naive-ui";
import {scrollbarThemeOverrides} from "@/themeOverrides.js";
import {onMounted, ref} from "vue";

const props = defineProps({
    channelId: Number,
})

const reviews = ref({})

const getChannelReviews = () => {
    axios.get(route('catalog.channel.reviews', {channelId: props.channelId}))
        .then(r => reviews.value = r.data)
        .catch(c => console.log(c))
}

onMounted(() => getChannelReviews())
</script>

<template>
    <div v-if="Object.keys(reviews).length" class="w-full reviews p-6">
        <n-scrollbar :theme-overrides="scrollbarThemeOverrides" class="max-h-96 ">
            <div class="grid sm:grid-cols-2 grid-cols-1 gap-5 px-1">
                <template v-for="review in reviews">
                    <ReviewsCard :review="review"/>
                </template>
            </div>
        </n-scrollbar>
    </div>
    <div v-else>
        <p class="text-violet-100 text-center text-2xl font-bold font-['Open Sans'] leading-10">Нет отзывов</p>
    </div>
</template>

<style scoped lang="scss">
.reviews{
    border-radius: 0px 30px 30px 30px;
    background: #5A5F77;
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);

}
</style>
