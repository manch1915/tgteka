<script setup>
import {NProgress} from "naive-ui";
import {ref} from "vue";

const props = defineProps({
    channel: Object
});

let ratingPercentage = ref(0);
let ratingDifference = ref(0);

if (props.channel) {
    const {
        position,
        rating,
        rating_diff
    } = props.channel;

    // You can adjust your top 'n' rating here
    const topRating = 10;

    ratingDifference.value = rating_diff;
    ratingPercentage.value = rating / topRating * 100;
}
</script>

<template>
    <div class="w-full about p-6">
        <p class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-relaxed">{{channel.description}}</p>
        <div class="about_card">
            <div class="about_card-header grid grid-cols-2 justify-center text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                <div class="flex justify-center py-5 border-b border-r border-violet-100 border-opacity-40">
                    <h2>По тематике</h2>
                </div>
                <div class="flex justify-center py-5 border-b border-violet-100 border-opacity-40">
                    <h2>Во всём каталоге</h2>
                </div>
            </div>
            <div class="p-5">
                <h1 class="text-center py-5 text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">{{ratingDifference}} рейтинга до топ 10 в каталоге</h1>
                <n-progress
                    type="line"
                    :show-indicator="false"
                    status="success"
                    :percentage="ratingPercentage"
                    color="#8729FF"
                    :height=20
                />
            </div>
            <div class="px-5 pb-5 border-b border-violet-100 border-opacity-40">
                <p class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">Вы занимаете <span class="font-bold">{{channel.position}}</span> место в каталоге по рейтингу.</p>
            </div>
            <div class="p-5 border-b border-violet-100 border-opacity-40">
                <p class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">Вам не хватает <span class="font-bold">{{ratingDifference}}</span> рейтинга, чтобы занять место в <span class="font-bold">топ 10</span>.</p>
            </div>
            <div class="p-5">
                <p class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">Чем выше вы будете в каталоге, тем больше заказов вы получите.<span class="underline"><br>Прочитайте</span> нашу статью, как увеличить рейтинг канала.</p>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.about{
    border-radius: 0 30px 30px 30px;
    background: #5A5F77;
    box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25);
    .about_card{
        border-radius: 30px;
        background: #181C37;
    }
}
</style>
