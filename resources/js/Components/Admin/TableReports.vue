<script setup>
import {computed, onMounted, ref, watch} from "vue";
import { mdiEye, mdiTrashCan } from "@mdi/js";
import CardBoxModal from "@/Components/Admin/CardBoxModal.vue";
import TableCheckboxCell from "@/Components/Admin/TableCheckboxCell.vue";
import BaseLevel from "@/Components/Admin/BaseLevel.vue";
import BaseButtons from "@/Components/Admin/BaseButtons.vue";
import BaseButton from "@/Components/Admin/BaseButton.vue";
import {router} from "@inertiajs/vue3";
import {NSelect} from "naive-ui";
import {trans} from "laravel-vue-i18n";

const items = ref([])
const statusValue = ref('pending')
const isModalDangerActive = ref(false);
const channelId = ref(null);

const perPage = ref(5);

const currentPage = ref(0);

const itemsPaginated = computed(() =>
    Object.values(items.value).slice(
        perPage.value * currentPage.value,
        perPage.value * (currentPage.value + 1)
    )
);

const numPages = computed(() => Math.ceil(Object.values(items.value).length / perPage.value));

const currentPageHuman = computed(() => currentPage.value + 1);

const pagesList = computed(() => {
  const pagesList = [];

  for (let i = 0; i < numPages.value; i++) {
    pagesList.push(i);
  }

  return pagesList;
});

const fetchReports = () => {
    axios.get(route('admin.api.reports.index', {status: statusValue.value}))
        .then(r => {
            items.value = r.data.data
        })
}

const statusOptions = [
    {
        value:'pending',
        label: trans('messages.pending'),
    },
    {
        value:'accepted',
        label: trans('messages.accepted'),
    },
    {
        value:'declined',
        label: trans('messages.declined'),
    }
]

onMounted(() => {
    fetchReports()
})

watch(
    () => statusValue.value,
    () => {
        fetchReports();
    },
);
</script>

<template>
    <div class="flex items-center gap-x-2 p-2">
        <p class="text-violet-100 text-lg font-bold font-['Open Sans']">Статус</p>
        <n-select v-model:value="statusValue" :options="statusOptions" class="my-4 w-64"/>
    </div>

  <CardBoxModal
    v-model="isModalDangerActive"
    title="Пожалуйста подтвердите"
    button="danger"
    @confirm="deleteTopic"
    has-cancel
  >
    <p>вы действительно хотите удалить?</p>
  </CardBoxModal>

  <table>
    <thead>
      <tr>
        <th>Id</th>
        <th>message</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="report in itemsPaginated" :key="report.id">
        <td data-label="Id">
          {{ report.id }}
        </td>
          <td data-label="Message">
          {{ report.message }}
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton
              color="info"
              :icon="mdiEye"
              small
              @click="router.visit(route('admin.api.reports.show', report.id))"
            />
          </BaseButtons>
        </td>
      </tr>
    </tbody>
  </table>
  <div class="p-3 lg:px-6 border-t border-gray-100 dark:border-slate-800">
    <BaseLevel>
      <BaseButtons>
        <BaseButton
          v-for="page in pagesList"
          :key="page"
          :active="page === currentPage"
          :label="page + 1"
          :color="page === currentPage ? 'lightDark' : 'whiteDark'"
          small
          @click="currentPage = page"
        />
      </BaseButtons>
      <small>Страница {{ currentPageHuman }} из {{ numPages }}</small>
    </BaseLevel>
  </div>
</template>
