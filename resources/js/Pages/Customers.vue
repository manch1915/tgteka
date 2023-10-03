<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import SloganBlock from "@/Components/Home/Blocks/SloganBlock.vue";
import MissionBlock from "@/Components/Home/Blocks/MissionBlock.vue";
import BookCard from "@/Components/Home/BookCard.vue";
import HowItWorksBlock from "@/Components/Home/Blocks/HowItWorksBlock.vue";
import PointCard from "@/Components/Home/PointCard.vue";
import InterestChannelsBlock from "@/Components/Home/Blocks/InterestChannelsBlock.vue";
import ChannelCard from "@/Components/Home/ChannelCard.vue";
import IntegrationBlock from "@/Components/Home/Blocks/IntegrationBlock.vue";
import ClientsBlock from "@/Components/Home/Blocks/ClientsBlock.vue";
import ClientsSlider from "@/Components/Home/ClientsSlider.vue";

const headers = ["Регистрируйте аккаунт", "Выберите подходящие каналы в каталоге", "Пополните баланс удобным способом:", "Создайте размещение с картинкой и ссылкой:", "Владелец канала публикует пост и присылает ссылку на проверку:", "Вы можете скачать отчет по итогу размещения:"]
const texts = ["Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc ",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc",
    "Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc"];
</script>

<template>
    <MainLayout>
        <SloganBlock/>
        <MissionBlock>
            <template v-slot:cards>
                <book-card v-for="i in 3" :key="i"/>
            </template>
        </MissionBlock>

        <HowItWorksBlock>
            <template v-slot:points>
                <point-card
                    v-for="i in 6"
                    :reversed="(i < 4)"
                    :header="headers[i-1]"
                    :paragraph="texts[i-1]"
                    :number="i"
                    :key="i"
                />
            </template>
        </HowItWorksBlock>
        <InterestChannelsBlock>
            <template v-slot:cards>
                <ChannelCard v-for="i in 6" :key="i"/>
            </template>
        </InterestChannelsBlock>
        <IntegrationBlock>
<!--            <template v-slot:comparePairs>-->
<!--                <ComparisonCard v-for=" (item, i) in compareItems" :item="item" :key="i" />-->
<!--            </template>-->
        </IntegrationBlock>
        <ClientsBlock>
            <template v-slot:slider>
                <clients-slider/>
            </template>
        </ClientsBlock>

    </MainLayout>
</template>

