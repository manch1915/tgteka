<script setup>
import {computed, onMounted, reactive, ref, toRefs, watch} from "vue";
import { mdiCancel, mdiCheckBold  } from "@mdi/js";
import TableCheckboxCell from "@/Components/Admin/TableCheckboxCell.vue";
import BaseLevel from "@/Components/Admin/BaseLevel.vue";
import BaseButtons from "@/Components/Admin/BaseButtons.vue";
import BaseButton from "@/Components/Admin/BaseButton.vue";
import {NSelect, useMessage} from "naive-ui";

defineProps({
  checkable: Boolean,
});

const message = useMessage();

const items = ref([])
const statusValue = ref('under_review')
const statusCounts = ref([]);

const state = reactive({
    statuses: [
        {
            value: 'pending',
            label: 'в ожидании',
        },
        {
            value: 'succeeded',
            label: 'законченный',
        },
        {
            value: 'failed',
            label: 'неуспешный',
        },
        {
            value: 'in_progress',
            label: 'в прогрессе',
        },
        {
            value: 'completed',
            label: 'завершенный',
        },
        {
            value: 'rejected',
            label: 'отклоненный',
        },
        {
            value: 'created',
            label: 'созданный',
        },
        {
            value: 'under_review',
            label: 'на рассмотрении',
        },
    ]
})

const { statuses } = toRefs(state);

const fetchCallbacks = () => {
    axios.get(route('admin.api.payouts.index', {status: statusValue.value}))
        .then(r => {
            items.value = r.data.data
        })
}

const fetchStatusCounts = () => {
    axios.get(route('admin.api.payout.count-statuses')) // updated route here
        .then(response => {
            statusCounts.value = response.data;
        });
}

watch(()=> statusValue.value, ()=>fetchCallbacks() )

const statusOptions = computed(() => {
    return statuses.value.map(status => {
        const count = statusCounts.value[status.value] ? statusCounts.value[status.value].count : 0;
        return {...status, label: `${status.label} (${count})`};
    });
});

onMounted(() => {
    fetchCallbacks()
    fetchStatusCounts()
})

const togglePayout = (status, id) => {
    axios.put(route('admin.api.payouts.update', id), { status: status, appointment: 'Вывод' })
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
    <div class="flex items-center gap-x-2 p-2">
        <p class="text-violet-100 text-lg font-bold font-['Open Sans']">Статус</p>
        <n-select v-model:value="statusValue" :options="statusOptions" class="my-4 w-64"/>
    </div>
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
        <th>id транзакции</th>
        <th>Имя пользователя</th>
        <th>Услуга</th>
        <th>Детали</th>
        <th>Сумма</th>
        <th>Статус</th>
        <th />
      </tr>
    </thead>
    <tbody>
      <tr v-for="transaction in itemsPaginated" :key="transaction.id">
        <TableCheckboxCell
          v-if="checkable"
          @checked="checked($event, transaction)"
        />
        <td data-label="id">
          {{ transaction.id }}
        </td>
        <td data-label="id транзакции">
          {{ transaction.transaction_id }}
        </td>
        <td data-label="Имя пользователя">
          {{ transaction.username }}
        </td>
        <td data-label="Услуга">
            {{ transaction.service }}
        </td>
        <td data-label="Детали">
            {{ transaction.details }}
        </td>
        <td data-label="Сумма">
            {{ transaction.amount }}&nbsp;₽
        </td>
        <td data-label="Статус">
          {{ transaction.status }}
        </td>
        <td class="before:hidden lg:w-1 whitespace-nowrap">
          <BaseButtons type="justify-start lg:justify-end" no-wrap v-if="transaction.status !== 'завершенный'">
            <BaseButton
              v-if="transaction.status === 'на рассмотрении'"
              color="success"
              :icon="mdiCheckBold"
              small
              @click="togglePayout('in_progress',transaction.id)"
              label="в прогрессе"
            />
            <BaseButton
              v-else
              color="success"
              :icon="mdiCheckBold"
              small
              @click="togglePayout('completed',transaction.id)"
              label="завершенный"
            />
            <BaseButton
              color="danger"
              :icon="mdiCancel"
              small
              @click="togglePayout('declined' ,transaction.id)"
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
