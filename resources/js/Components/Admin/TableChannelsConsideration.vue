<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiCheckBold, mdiTrashCan } from "@mdi/js";
import CardBoxModal from "@/Components/Admin/CardBoxModal.vue";
import TableCheckboxCell from "@/Components/Admin/TableCheckboxCell.vue";
import BaseLevel from "@/Components/Admin/BaseLevel.vue";
import BaseButtons from "@/Components/Admin/BaseButtons.vue";
import BaseButton from "@/Components/Admin/BaseButton.vue";
import UserAvatar from "@/Components/Admin/UserAvatar.vue";
import {NDatePicker} from "naive-ui";
import {datePickerThemeOverrides} from "@/themeOverrides.js";
import {router} from "@inertiajs/vue3";

defineProps({
  checkable: Boolean,
});

const mainStore = useMainStore();
mainStore.fetchChannels()
const items = computed(() => Object.values(mainStore.channels).filter(channel => channel.status === 'pending'));

const isModalDangerActive = ref(false);

const timestamp = ref()

const modalDangerActive = (c) => {
    isModalDangerActive.value = true
    modalChannel.value = c
}

const channelId = ref(null);
const declineChannel = () => {
    axios.patch(route('admin.api.channels.update', modalChannel.value.slug), {status: 'declined'})
        .then(r => {
            console.log(r);
            mainStore.fetchChannels();
        })
        .catch(e => console.log(e))
}

const channelAccept = () => {
    axios.patch(route('admin.api.channels.update', modalChannel.value.slug), {status: 'loading', channel_creation_date: timestamp.value})
        .then(r => {
            console.log(r);
            mainStore.fetchChannels(); // Fetch after accepting a channel
        })
        .catch(e => console.log(e))
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
    @confirm="declineChannel"
    has-cancel
    button-label="Отклонить канал"
  >
    <p>вы действительно хотите отклонить этот канал?</p>
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
            :avatar="channel.avatar"
            :username="channel.channel_name"
            class="w-24 mx-auto lg:w-6 lg:h-6"
          />
        </td>
        <td data-label="Name">
          {{ channel.channel_name }}
        </td>
        <td data-label="Company">
            {{ truncateDescription(channel.description, 20) }}
        </td>
        <td v-if="channel.topic.title" data-label="topic">
          {{ channel.topic.title }}
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton
              color="success"
              :icon="mdiCheckBold"
              small
              @click="router.visit(route('admin.api.channels.edit', channel.slug))"
            />
            <BaseButton
              color="danger"
              :icon="mdiTrashCan"
              small
              @click="modalDangerActive(channel)"
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
      <small>Page {{ currentPageHuman }} of {{ numPages }}</small>
    </BaseLevel>
  </div>
</template>
