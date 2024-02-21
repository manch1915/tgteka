<script setup>
import {onMounted, ref} from "vue";
import {Line} from "vue-chartjs";
import {
    BarElement,
    CategoryScale,
    Chart,
    Legend,
    LinearScale,
    LineElement,
    PointElement,
    Title,
    Tooltip
} from "chart.js";
import axios from "axios";
import {useMessage} from "naive-ui";

const props = defineProps({
    channel_id: Number,
})

const message = useMessage()

Chart.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement, LineElement);
Chart.defaults.color = '#EAE0FF';
Chart.defaults.borderColor = '#E6E6E6';

const channelStats = ref({})

let chartDataSubs = ref({
    labels: [],
    datasets: [{
        label: 'Subscribers',
        data: [],
        borderColor: '#8729FF',
        borderWidth: '3',
        tension: '0.4',
        pointBorderWidth: '1',
        fill: false,
    }]
})

let chartDataAvg = ref({
    labels: [],
    datasets: [{
        label: 'Avg Post Reach',
        data: [],
        borderColor: '#8729FF',
        borderWidth: '3',
        tension: '0.4',
        pointBorderWidth: '1',
        fill: false,
    }]
})

let chartDataER = ref({
    labels: [],
    datasets: [{
        label: 'ER',
        data: [],
        borderColor: '#8729FF',
        borderWidth: '3',
        pointBorderWidth: '1',
        tension: '0.4',
    }]
})

const fetchChannelStats = async () => {
    try {
        const response = await axios.get(route('catalog.channel.stats.all', props.channel_id))
        channelStats.value = response.data;

        chartDataSubs.value = {
            labels: channelStats.value.subscribers.reverse().map(item => {
                let date = new Date(item.period);
                return `${date.getMonth() + 1}-${date.getDate()}`;}),
            datasets: [{
                label: 'Subscribers',
                data: channelStats.value.subscribers.map(item => item.participants_count),
                borderColor: '#8729FF',
                fill: false,
            }]
        };
        chartDataAvg.value = {
            labels: channelStats.value.avg_posts_reach.reverse().map(item => {
                let date = new Date(item.period);
                return `${date.getMonth() + 1}-${date.getDate()}`;}),
            datasets: [{
                label: 'avg_posts_reach',
                data: channelStats.value.avg_posts_reach.map(item => item.avg_posts_reach),
                borderColor: '#8729FF',
                fill: false,
            }]
        };
        chartDataER.value = {
            labels: channelStats.value.er.reverse().map(item => {
                let date = new Date(item.period);
                return `${date.getMonth() + 1}-${date.getDate()}`;}),
            datasets: [{
                label: 'ER',
                data: channelStats.value.er.map(item => item.er),
                borderColor: '#8729FF',
                fill: false,
            }]
        };
    }
    catch (err) {
        if (err.response.data.error){
            message.error(err.response.data.error)
        }
    }
}

onMounted(() => {
    fetchChannelStats();
});

</script>

<template>
    <div class="w-full reviews p-6">
        <div class="grid sm:grid-cols-4 grid-cols-2 gap-4 mb-2">
            <div class="item text-center !py-10">
                <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Рейтинг</p>
                <p v-if="channelStats.channel" class="text-violet-100 text-base font-bold font-['Open Sans'] leading-tight">{{channelStats.channel.rating || 0}}</p>
            </div>
            <div class="item text-center !py-10">
                <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Выполнено заявок</p>
                <p class="text-violet-100 text-base font-bold font-['Open Sans'] leading-tight">{{channelStats.finished_orders_count}}</p>
            </div>
            <div class="item">

            </div>
            <div class="item item text-center !py-10">
                <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Денег заработано</p>
                <p class="text-violet-100 text-base font-bold font-['Open Sans'] leading-tight">{{channelStats.finished_orders_price_sum}}</p>
            </div>
        </div>
        <div class="grid sm:grid-cols-2 grid-cols-1 gap-4">
            <div class="item">
                <div class="grid sm:grid-cols-2 grid-cols-1">
                    <div>
                        <h1 class="text-center text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">Подписчики</h1>
                        <Line :data="chartDataSubs"/>
                    </div>
                    <div>
                        <div v-if="channelStats.stats">
                            <div class="text-violet-100 sm:text-2xl text-lg font-bold font-['Open Sans'] leading-loose">{{ channelStats.stats.participants_count }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="grid sm:grid-cols-2 grid-cols-1">
                    <div>
                        <h1 class="text-center text-violet-100 sm:text-xl text-lg font-normal font-['Open Sans'] leading-relaxed">Охват </h1>
                        <Line :data="chartDataAvg"/>
                    </div>
                    <div>
                        <div v-if="channelStats.stats">
                            <div class="text-violet-100 sm:text-2xl text-lg font-bold font-['Open Sans'] sm:leading-loose">Охват за 24 часа: {{ channelStats.stats.adv_post_reach_24h }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="grid sm:grid-cols-2 grid-cols-1">
                    <div>
                        <h1 class="text-center text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">ER% — вовлеченность по взаимодействиям</h1>
                        <Line :data="chartDataER"/>
                    </div>
                    <div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.reviews{
    border-radius: 0px 30px 30px 30px;
    background: #5A5F77;
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
    .item{
        border-radius: 10px;
        background:  #0D143A;
        padding: 10px;
    }
}

</style>
