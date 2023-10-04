<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import MissionBlock from "@/Components/Home/Blocks/MissionBlock.vue";
import BookCard from "@/Components/Home/BookCard.vue";
import PointCard from "@/Components/Home/PointCard.vue";
import HowItWorksBlock from "@/Components/Home/Blocks/HowItWorksBlock.vue";
import IntegrationBackground from "@/Components/Home/Blocks/IntegrationBackground.vue";
import ClientsBlock from "@/Components/Home/Blocks/ClientsBlock.vue";
import ClientsSlider from "@/Components/Home/ClientsSlider.vue";

const headers = ["Добавляете канал в каталог", "Получаете заказы и размещаете посты", "Получаете оплату"]
const texts = ["В среднем модерация занимает 24 часа ", "Оповещения о новых размещениях приходят на E-mail и в Telegram-бота",
    "Вы сможете выводить средства от 1 000 рублей три раза в неделю"];
</script>

<template>
    <MainLayout>
        <MissionBlock>
            <template v-slot:cards>
                <book-card v-for="i in 3" :key="i"/>
            </template>
        </MissionBlock>
        <HowItWorksBlock>
            <template v-slot:points>
                <point-card
                    v-for="i in 3"
                    :reversed="false"
                    :header="headers[i-1]"
                    :paragraph="texts[i-1]"
                    :number="i"
                    :key="i"
                />
            </template>
        </HowItWorksBlock>
        <div class="py-44">
            <IntegrationBackground/>
        </div>
        <ClientsBlock>
            <template v-slot:slider>
                <clients-slider/>
            </template>
        </ClientsBlock>
    </MainLayout>
</template>

<style scoped lang="scss">

</style>
