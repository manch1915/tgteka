<script setup>
import {
  mdiTableBorder,
} from "@mdi/js";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import CardBox from "@/Components/Admin/CardBox.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/Admin/SectionTitleLineWithButton.vue";
import TablePayouts from "@/Components/Admin/TablePayouts.vue";
import PayoutsTableWithSearch from '@/Components/Admin/PayoutsTableWithSearch.vue'

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Выводы" main/>

      <CardBox class="mb-6" has-table>
          <PayoutsTableWithSearch/>
      </CardBox>

    </SectionMain>
  </LayoutAuthenticated>
</template>
