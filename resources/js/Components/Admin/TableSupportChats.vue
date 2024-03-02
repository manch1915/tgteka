<script setup>
import { computed, ref } from "vue";
import { useMainStore } from "@/stores/main";
import {mdiArrowLeftBold, mdiEye, mdiTrashCan} from "@mdi/js";
import CardBoxModal from "@/Components/Admin/CardBoxModal.vue";
import TableCheckboxCell from "@/Components/Admin/TableCheckboxCell.vue";
import BaseLevel from "@/Components/Admin/BaseLevel.vue";
import BaseButtons from "@/Components/Admin/BaseButtons.vue";
import BaseButton from "@/Components/Admin/BaseButton.vue";
import {openModal} from "jenesius-vue-modal";
import Messanger from "@/Components/Dashboard/Messanger.vue";
import {usePage} from "@inertiajs/vue3";
import {NDrawer, NDrawerContent} from "naive-ui";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";

defineProps({
  checkable: Boolean,
});

const mainStore = useMainStore();
mainStore.fetchSupportChats()
const items = computed(() => mainStore.supportChats);

const isModalDangerActive = ref(false);
const chatId = ref(null);
const deleteChat = () => {
    console.log(chatId.value)
    axios.delete(route('admin.api.support.destroy', chatId.value))
        .then(() => mainStore.fetchSupportChats())
        .catch(e => console.log(e))
}
const deletingChat = (id) => {
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
const page = usePage()
const userId = computed(() => page.props.auth.user.id)
const socket = new WebSocket(`wss://${import.meta.env.VITE_APP_WEBSOCKETS_IP}:1915/?userid=${userId.value}`);
socket.onerror = function(error) {
    console.log(error);
};
const active = ref(false)
const ticketsId = ref(null)

const openMessengerModal = (ticketId) =>{
    active.value = true
    ticketsId.value = ticketId
}
</script>

<template>
    <n-drawer v-model:show="active" close-on-esc width="100%" :theme-overrides="{color: '#070C29'}">
        <n-drawer-content>
            <template #header>
                <div @click.prevent="active = false" class="flex cursor-pointer">
                    <base-icon :path="mdiArrowLeftBold" size="24"/>
                    <p class="text-violet-100 text-lg font-bold font-['Open Sans']">Чат</p>
                </div>
            </template>
            <div class="w-full flex justify-center">
                <Messanger :tickets="ticketsId" :socket="socket" :user-id="userId"></Messanger>
            </div>
        </n-drawer-content>
    </n-drawer>
  <CardBoxModal
    v-model="isModalDangerActive"
    title="Пожалуйста подтвердите"
    button="danger"
    @confirm="deleteChat"
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
        <th>Название</th>
        <th>created_at</th>
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="chat in itemsPaginated" :key="chat.id">
        <TableCheckboxCell
          v-if="checkable"
          @checked="checked($event, chat)"
        />
        <td data-label="Title">
          {{ chat.title }}
        </td>
        <td data-label="created_at">
          {{ chat.created_at }}
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton
              color="info"
              :icon="mdiEye"
              small
              @click="openMessengerModal(chat.id)"
            />
            <BaseButton
              color="danger"
              :icon="mdiTrashCan"
              small
              @click="deletingChat(chat.id)"
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
