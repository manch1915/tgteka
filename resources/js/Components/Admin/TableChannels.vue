<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiEye, mdiTrashCan } from "@mdi/js";
import CardBoxModal from "@/Components/Admin/CardBoxModal.vue";
import TableCheckboxCell from "@/Components/Admin/TableCheckboxCell.vue";
import BaseLevel from "@/Components/Admin/BaseLevel.vue";
import BaseButtons from "@/Components/Admin/BaseButtons.vue";
import BaseButton from "@/Components/Admin/BaseButton.vue";
import UserAvatar from "@/Components/Admin/UserAvatar.vue";
import {router} from "@inertiajs/vue3";

defineProps({
  checkable: Boolean,
});

const mainStore = useMainStore();
mainStore.fetchChannels()
const items = computed(() => mainStore.channels);

const isModalActive = ref(false);

const isModalDangerActive = ref(false);
const channelId = ref(null);
const deleteChannel = () => {
    axios.delete(route('admin.api.channels.destroy', channelId.value))
        .then(r => console.log(r))
        .catch(e => console.log(e))
}
const deletingChannel = (id) => {
    isModalDangerActive.value = true
    channelId.value = id
}

const perPage = ref(5);

const currentPage = ref(0);

const checkedRows = ref([]);

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

const remove = (arr, cb) => {
  const newArr = [];

  arr.forEach((item) => {
    if (!cb(item)) {
      newArr.push(item);
    }
  });

  return newArr;
};
const truncateDescription = (description, wordLimit) => {
    if (!description) {
        return ''; // or handle it in a way that makes sense for your application
    }

    const words = description.split(' ');
    return words.length > wordLimit ? words.slice(0, wordLimit).join(' ') + '...' : description;
};
const checked = (isChecked, client) => {
  if (isChecked) {
    checkedRows.value.push(client);
  } else {
    checkedRows.value = remove(
      checkedRows.value,
      (row) => row.id === client.id
    );
  }
};
</script>

<template>

  <CardBoxModal
    v-model="isModalDangerActive"
    title="Пожалуйста подтвердите"
    button="danger"
    @confirm="deleteChannel"
    has-cancel
  >
    <p>вы действительно хотите удалить этот канал?</p>
  </CardBoxModal>

  <div v-if="checkedRows.length" class="p-3 bg-gray-100/50 dark:bg-slate-800">
    <span
      v-for="checkedRow in checkedRows"
      :key="checkedRow.id"
      class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
    >
      {{ checkedRow.name }}
    </span>
  </div>

  <table>
    <thead>
      <tr>
        <th v-if="checkable" />
        <th />
        <th>Название</th>
        <th>Описание</th>
        <th>Тематика</th>
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="channel in itemsPaginated" :key="channel.id">
        <TableCheckboxCell
          v-if="checkable"
          @checked="checked($event, channel)"
        />
        <td class="border-b-0 lg:w-6 before:hidden">
          <UserAvatar
            api="initials"
            :avatar="channel.avatar"
            :username="channel.channel_name"
            class="w-24 mx-auto lg:w-6 lg:h-6"
          />
        </td>
        <td data-label="Название">
          {{ channel.channel_name }}
        </td>
        <td data-label="Описание">
            {{ truncateDescription(channel.description, 20) }}
        </td>
        <td data-label="Тематика">
          {{ channel.topic.title }}
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton
              color="info"
              :icon="mdiEye"
              small
              @click="router.visit(route('admin.api.channels.edit', channel.slug))"
            />
            <BaseButton
              color="danger"
              :icon="mdiTrashCan"
              small
              @click="deletingChannel(channel.id)"
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
<style scoped>
td{
    word-break: break-all;
}
</style>
