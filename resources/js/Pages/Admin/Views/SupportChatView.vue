<script setup>
import {
     mdiChat, mdiReload
} from "@mdi/js";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/Admin/SectionTitleLineWithButton.vue";
import TableSupportChats from "@/Components/Admin/TableSupportChats.vue";
import CardBox from "@/Components/Admin/CardBox.vue";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {useMainStore} from "@/stores/main.js";

const mainStore = useMainStore();
mainStore.fetchSupportChats()
</script>

<template>
  <LayoutAuthenticated>
      <SectionMain>
          <SectionTitleLineWithButton :icon="mdiChat" title="Чаты" >
              <BaseIcon class="cursor-pointer" :path="mdiReload" @click.prevent="mainStore.fetchSupportChats()"/>
          </SectionTitleLineWithButton>
          <CardBox class="mb-6" has-table>
            <TableSupportChats/>
          </CardBox>
      </SectionMain>
  </LayoutAuthenticated>
</template>
