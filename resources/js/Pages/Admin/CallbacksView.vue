<script setup>
import {
  mdiTableBorder,
} from "@mdi/js";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import CardBox from "@/Components/Admin/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/Admin/SectionTitleLineWithButton.vue";
import TableCallbacks from "@/Components/Admin/TableCallbacks.vue";
import CallbackTableWithSearch from '@/Components/Admin/CallbackTableWithSearch.vue'

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Обратные вызовы" main/>

      <CardBox class="mb-6" has-table>
        <CallbackTableWithSearch/>
      </CardBox>

    </SectionMain>
  </LayoutAuthenticated>
</template>
