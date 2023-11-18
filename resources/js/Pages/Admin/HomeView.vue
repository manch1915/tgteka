<script setup>
import { computed, ref, onMounted } from "vue";
import { useMainStore } from "@/stores/main";
import {
  mdiAccountMultiple,
    mdiFileCabinet,
    mdiAccount ,
  mdiMonitorCellphone,
  mdiReload,
  mdiChartPie,
} from "@mdi/js";
import * as chartConfig from "@/Components/Admin/Charts/chart.config.js";
import LineChart from "@/Components/Admin/Charts/LineChart.vue";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import CardBoxWidget from "@/Components/Admin/CardBoxWidget.vue";
import CardBox from "@/Components/Admin/CardBox.vue";
import NotificationBar from "@/Components/Admin/NotificationBar.vue";
import BaseButton from "@/Components/Admin/BaseButton.vue";
import CardBoxTransaction from "@/Components/Admin/CardBoxTransaction.vue";
import CardBoxClient from "@/Components/Admin/CardBoxClient.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/Admin/SectionTitleLineWithButton.vue";

const chartData = ref(null);

const fillChartData = () => {
  chartData.value = chartConfig.sampleChartData();
};

onMounted(() => {
  fillChartData();
});

const props = defineProps({
    channelsCount: Number,
    usersCount: Number
})

const mainStore = useMainStore();

const transactionBarItems = computed(() => mainStore.history);
</script>

<template>
  <LayoutAuthenticated>
      <SectionMain>
          <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-6">
              <CardBoxWidget
                  trend=""
                  trend-type="up"
                  color="text-emerald-500"
                  :icon="mdiAccount "
                  :number="usersCount"
                  label="Пользователи"
              />
              <CardBoxWidget
                  trend=""
                  trend-type="down"
                  color="text-blue-500"
                  :icon="mdiFileCabinet "
                  :number="channelsCount"
                  label="Каналы"
              />
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
              <div class="flex flex-col justify-between">
                  <CardBoxTransaction
                      v-for="(transaction, index) in transactionBarItems"
                      :key="index"
                      :amount="transaction.amount"
                      :date="transaction.date"
                      :business="transaction.business"
                      :type="transaction.type"
                      :name="transaction.name"
                      :account="transaction.account"
                  />
              </div>
              <div class="flex flex-col justify-between">
                  <CardBoxClient
                      v-for="client in clientBarItems"
                      :key="client.id"
                      :name="client.name"
                      :login="client.login"
                      :date="client.created"
                      :progress="client.progress"
                  />
              </div>
          </div>

          <SectionTitleLineWithButton :icon="mdiChartPie" title="Trends overview">
              <BaseButton
                  :icon="mdiReload"
                  color="whiteDark"
                  @click="fillChartData"
              />
          </SectionTitleLineWithButton>

          <CardBox class="mb-6">
              <div v-if="chartData">
                  <line-chart :data="chartData" class="h-96" />
              </div>
          </CardBox>

          <SectionTitleLineWithButton :icon="mdiAccountMultiple" title="Clients" />

          <NotificationBar color="info" :icon="mdiMonitorCellphone">
              <b>Responsive table.</b> Collapses on mobile
          </NotificationBar>

      </SectionMain>
  </LayoutAuthenticated>
</template>
