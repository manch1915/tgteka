<script setup>
import { computed, ref, onMounted} from "vue";
import { useMessage } from "naive-ui";
import { mdiEye, mdiTrashCan, mdiShieldCrown } from "@mdi/js";
import CardBoxModal from "@/Components/Admin/CardBoxModal.vue";
import TableCheckboxCell from "@/Components/Admin/TableCheckboxCell.vue";
import BaseLevel from "@/Components/Admin/BaseLevel.vue";
import BaseButtons from "@/Components/Admin/BaseButtons.vue";
import BaseButton from "@/Components/Admin/BaseButton.vue";
import UserAvatar from "@/Components/Admin/UserAvatar.vue";

defineProps({
    checkable: Boolean,
});

const message = useMessage();

const items = ref([])
const fetchUsers = () => {
    axios.get(route('admin.api.users.index'))
        .then(r => {
            items.value = r.data.data
        })
}

onMounted(() => {
    fetchUsers()
})

const isModalActive = ref(false);
const isModalAdminActive = ref(false);

const isModalDangerActive = ref(false);
const userId = ref(null);
const deleteUser = () => {
    axios.delete(route('admin.api.users.destroy', userId.value))
        .then(r => message.info(r.data))
        .catch(e => console.log(e))
}

const changeUserRole = () => {
    axios.put(route('admin.api.users.update', userToShow.value.id))
        .then(r => {
            message.info(r.data.status);
            isModalAdminActive.value = false
        })
        .catch(e => console.log(e))
}

const deletingUser = (id) => {
    isModalDangerActive.value = true
    userId.value = id
}
const userToShow = ref({})
const showUser = (user) => {
    isModalActive.value = true
    userToShow.value = user
}
const showAdminUser = (user) => {
    isModalAdminActive.value = true
    userToShow.value = user
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
    <CardBoxModal button-label="Закрыть" v-model="isModalActive" title="Sample modal">
        <table class="table-auto">
            <tbody>
            <tr>
                <td>ID</td>
                <td>{{userToShow.id}}</td>
            </tr>
            <tr>
                <td>Имя</td>
                <td>{{userToShow.username}}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{userToShow.email}}</td>
            </tr>
            <tr>
                <td>Номер телефона</td>
                <td>{{userToShow.mobile_number}}</td>
            </tr>
            <tr>
                <td>Имя пользователя Telegram</td>
                <td>{{userToShow.telegram_username}}</td>
            </tr>
            <tr>
                <td>Баланс</td>
                <td>{{userToShow.balance}}</td>
            </tr>
            <tr>
                <td>Количество заказов на канале</td>
                <td>{{userToShow.channel_orders_count}}</td>
            </tr>
            <tr>
                <td>Количество каналов</td>
                <td>{{userToShow.channels_count}}</td>
            </tr>
            <tr>
                <td>Количество заказов</td>
                <td>{{userToShow.orders_count}}</td>
            </tr>
            <tr>
                <td>Количество шаблонов</td>
                <td>{{userToShow.patterns_count}}</td>
            </tr>
            </tbody>
        </table>
    </CardBoxModal>

    <CardBoxModal
        v-model="isModalDangerActive"
        title="Пожалуйста подтвердите"
        button="danger"
        @confirm="deleteUser"
        button-label="Подтвердить"
        has-cancel
    >
        <p>вы действительно хотите удалить этого пользователя?</p>
    </CardBoxModal>
    <CardBoxModal
        v-model="isModalAdminActive"
        title="Изменение прав админа"
        button-label="Закрыть"
    >
        <p v-if="userToShow.is_moderator">Изменить права пользователя с "Модератор" на "Пользователь"?</p>
        <p v-else>Изменить права пользователя на "Модератор"?</p>
        <BaseButton
            color="info"
            :icon="mdiShieldCrown"
            @click="changeUserRole"
            label="Изменить"
        />
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
            <th>Имя</th>
            <th>Email</th>
            <th>Номер телефона</th>
            <th>Баланс</th>
            <th />
        </tr>
        </thead>
        <tbody>
        <tr v-for="user in itemsPaginated" :key="user.id">
            <TableCheckboxCell
                v-if="checkable"
                @checked="checked($event, user)"
            />
            <td class="border-b-0 lg:w-6 before:hidden">
                <UserAvatar
                    :username="user.name ? user.name : user.email"
                    class="w-24 mx-auto lg:w-6 lg:h-6"
                />
            </td>
            <td data-label="Имя">
                {{ user.username }}
            </td>
            <td data-label="Email">
                {{ user.email }}
            </td>
            <td data-label="Номер телефона">
                {{ user.mobile_number }}
            </td>
            <td data-label="Баланс">
                {{ user.balance }}₽
            </td>
            <td class="before:hidden lg:w-1 whitespace-nowrap">
                <BaseButtons type="justify-start lg:justify-end" no-wrap>
                    <BaseButton
                        color="info"
                        :icon="mdiEye"
                        small
                        @click="showUser(user)"
                    />
                    <BaseButton
                        color="info"
                        :icon="mdiShieldCrown"
                        small
                        @click="showAdminUser(user)"
                    />
                    <BaseButton
                        color="danger"
                        :icon="mdiTrashCan"
                        small
                        @click="deletingUser(user.id)"
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
