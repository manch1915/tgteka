<script setup>
import {
    mdiTableBorder,
} from "@mdi/js";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/Admin/SectionTitleLineWithButton.vue";
import CardBox from "@/Components/Admin/CardBox.vue";
import OrdersTableWithSearch from "@/Components/Admin/Tables/OrdersTableWithSearch.vue"

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Заявки" main/>
        <CardBox class="mb-6 mt-12" has-table>
            <OrdersTableWithSearch/>
        </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
