<script setup>
import {
  mdiAccountMultiple
} from "@mdi/js";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/Admin/SectionTitleLineWithButton.vue";
import TableSupportChats from "@/Components/Admin/TableSupportChats.vue";
import CardBox from "@/Components/Admin/CardBox.vue";

</script>

<template>
  <LayoutAuthenticated>
      <SectionMain>
          <SectionTitleLineWithButton :icon="mdiAccountMultiple" title="Чаты" />
          <CardBox class="mb-6" has-table>
            <TableSupportChats/>
          </CardBox>
      </SectionMain>
  </LayoutAuthenticated>
</template>
