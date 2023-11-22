<script setup>
import {
    mdiFileCabinet,
    mdiAccount
} from "@mdi/js";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import CardBoxWidget from "@/Components/Admin/CardBoxWidget.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";

const props = defineProps({
    channelsCount: Number,
    usersCount: Number
})

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

      </SectionMain>
  </LayoutAuthenticated>
</template>
