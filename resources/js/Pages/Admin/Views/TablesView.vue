<script setup>
import {
  mdiTableBorder,
} from "@mdi/js";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/Admin/SectionTitleLineWithButton.vue";
import TableWithSearch from '@/Components/Admin/Tables/TableWithSearch.vue'

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Каналы" main/>
        <div class="main-section">
            <TableWithSearch />
        </div>
    </SectionMain>
  </LayoutAuthenticated>
</template>
