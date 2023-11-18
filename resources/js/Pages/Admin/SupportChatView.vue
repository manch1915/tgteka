<script setup>
import {
  mdiAccountMultiple
} from "@mdi/js";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/Admin/SectionTitleLineWithButton.vue";
import TableSupportChats from "@/Components/Admin/TableSupportChats.vue";

</script>

<template>
  <LayoutAuthenticated>
      <SectionMain>
          <SectionTitleLineWithButton :icon="mdiAccountMultiple" title="Чаты" />
            <TableSupportChats/>
      </SectionMain>
  </LayoutAuthenticated>
</template>
