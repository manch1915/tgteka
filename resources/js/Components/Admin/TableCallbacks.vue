<script setup>
import { computed, onMounted, ref } from "vue";
import { useMainStore } from "@/stores/main";
import { mdiCancel, mdiCheckBold, mdiEye, mdiTrashCan } from "@mdi/js";
import CardBoxModal from "@/Components/Admin/CardBoxModal.vue";
import TableCheckboxCell from "@/Components/Admin/TableCheckboxCell.vue";
import BaseLevel from "@/Components/Admin/BaseLevel.vue";
import BaseButtons from "@/Components/Admin/BaseButtons.vue";
import BaseButton from "@/Components/Admin/BaseButton.vue";
import UserAvatar from "@/Components/Admin/UserAvatar.vue";
import { useMessage } from "naive-ui";

defineProps({
  checkable: Boolean,
});

const message = useMessage();

const items = ref([])
const fetchCallbacks = () => {
    axios.get(route('admin.api.callbacks.index'))
        .then(r => {
            items.value = r.data.data
        })
}
onMounted(() => {
    fetchCallbacks()
})

const toggleCallback = (status, id) => {
    axios.put(route('admin.api.callbacks.update', id), { status: status })
        .then(r => message.info(r.data.message))
        .catch(e => console.log(e))
    fetchCallbacks()
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
        <th>id</th>
        <th>Имя</th>
        <th>Номер телефона</th>
        <th>Статус</th>
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="callback in itemsPaginated" :key="callback.id">
        <TableCheckboxCell
          v-if="checkable"
          @checked="checked($event, callback)"
        />
        <td data-label="id">
          {{ callback.id }}
        </td>
        <td data-label="Название">
          {{ callback.name }}
        </td>
        <td data-label="Email">
            {{ callback.email }}
        </td>
        <td data-label="Статус">
          {{ callback.status }}
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap>
            <BaseButton
              color="success"
              :icon="mdiCheckBold"
              small
              @click="toggleCallback('finished',callback.id)"
            />
            <BaseButton
              color="danger"
              :icon="mdiCancel"
              small
              @click="toggleCallback('declined' ,callback.id)"
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
