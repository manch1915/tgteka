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

defineProps({
  checkable: Boolean,
});

const mainStore = useMainStore();
mainStore.fetchChannels()
const items = computed(() => Object.values(mainStore.chats).filter(chat => chat.status === 'pending'));

const isModalActive = ref(false);
const modalChat = ref({})
const modalChatActive = (c) => {
    isModalActive.value = true
    modalChat.value = c
}

const isModalDangerActive = ref(false);
const chatId = ref(null);
const deleteChat = () => {
    axios.delete(route('admin.api.channels.destroy', chatId.value))
        .then(r => console.log(r))
        .catch(e => console.log(e))
}

const channelAccept = () => {
    axios.patch(route('admin.api.channels.update', modalChat.value.id), {status: 'accepted'})
        .then(r => {
            console.log(r);
            mainStore.fetchChannels(); // Fetch after accepting a channel
        })
        .catch(e => console.log(e))
}

const deletingChannel = (id) => {
    isModalDangerActive.value = true
    chatId.value = id
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
        return '';
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
  <CardBoxModal @confirm="channelAccept" button="success" has-cancel v-model="isModalActive" title="Просмотр канала">
    <a class="text-blue-800" :href="modalChat.channel_url">{{modalChat.channel_name}}</a>
    <p>{{modalChat.description}}</p>
    <p>{{modalChat.subscribers_source}}</p>
    <p>{{modalChat.format_one}}</p>
    <p>{{modalChat.format_two}}</p>
    <p>{{modalChat.format_three}}</p>
    <p>{{modalChat.no_deletion}}</p>
    <p>{{modalChat.language}}</p>
    <p>{{modalChat.topic}}</p>
  </CardBoxModal>

  <CardBoxModal
    v-model="isModalDangerActive"
    title="Пожалуйста подтвердите"
    button="danger"
    @confirm="deleteChat"
    has-cancel
  >
    <p>вы действительно хотите удалить этот чат?</p>
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
      <tr v-for="chat in itemsPaginated" :key="chat.id">
        <TableCheckboxCell
          v-if="checkable"
          @checked="checked($event, chat)"
        />
        <td class="border-b-0 lg:w-6 before:hidden">
          <UserAvatar
            :avatar="chat.avatar"
            :username="chat.chat_name"
            class="w-24 h-24 mx-auto lg:w-6 lg:h-6"
          />
        </td>
        <td data-label="Name">
          {{ chat.channel_name }}
        </td>
        <td data-label="Company">
            {{ truncateDescription(chat.description, 20) }}
        </td>
        <td data-label="topic">
          {{ chat.topic }}
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton
              color="success"
              :icon="mdiCheckBold"
              small
              @click="modalChatActive(chat)"
            />
            <BaseButton
              color="danger"
              :icon="mdiTrashCan"
              small
              @click="deletingChannel(chat.id)"
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
