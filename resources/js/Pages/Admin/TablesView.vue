<script setup>
import {
  mdiTableBorder,
} from "@mdi/js";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import CardBox from "@/Components/Admin/CardBox.vue";
import LayoutAuthenticated from "@/layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/Admin/SectionTitleLineWithButton.vue";
import TableChannels from "@/Components/Admin/TableChannels.vue";
import TableChannelsConsideration from "@/Components/Admin/TableChannelsConsideration.vue";

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Каналы" main/>

      <CardBox class="mb-6" has-table>
        <TableChannels/>
      </CardBox>

      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Каналы на рассмотрение" main/>

      <CardBox class="mb-6" has-table>
        <TableChannelsConsideration/>
      </CardBox>

    </SectionMain>
  </LayoutAuthenticated>
</template>
