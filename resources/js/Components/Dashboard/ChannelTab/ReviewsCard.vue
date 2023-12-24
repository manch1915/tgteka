<script setup>
import {NRate} from "naive-ui";
import {computed} from "vue";

const props = defineProps({
    review: Object
})

const formatDate = computed(() => {
    if (props.review.created_at) {
        let date = new Date(props.review.created_at);
        let options = { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Intl.DateTimeFormat('ru-RU', options).format(date);
    }
});
</script>

<template>
    <div class="reviews_card">
        <div class="reviews_card_main p-3">
            <div class="flex gap-x-2">
                <img class="w-6 rounded-full" src="/images/avatar.jpg" alt="">
                <p class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">{{ review.user.email}}</p>
            </div>
            <div class="py-4 border-b border-black border-opacity-60">
                <p class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">{{ review.review_text }}</p>
            </div>
            <div class="flex justify-between pt-4">
                <p class="w-36 text-violet-100 text-xs font-normal font-['Poppins'] leading-none">{{ formatDate }}</p>
                <n-rate :value="review.rating" readonly/>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.reviews_card{
    padding: 3px;
    background: linear-gradient(to right bottom, #151515, #5A5277, #1F304E);
    border-radius: 0px 25px 25px 25px;
    .reviews_card_main{
        background: linear-gradient(to right bottom,#9A9CAC, #595D78);
        border-radius: 0px 25px 25px 25px;
    }
}
</style>
