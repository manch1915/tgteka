<template>
    <div class="main-container">
        <TableBody ref="tableBody">
            <template #header>
                <TableHeader
                    :show-filter="true"
                    title="Условия запроса"
                    @search="onSearch"
                    @reset-search="onResetSearch"
                >
                    <template #search-content>
                        <DataForm
                            ref="searchForm"
                            :form-config="{
                                labelWidth: 70,
                            }"
                            :options="conditionItems"
                            preset="grid-item"
                        />
                    </template>
                </TableHeader>
            </template>
            <template #default>
                <div style="overflow: auto">
                <n-data-table
                    size="small"
                    :loading="tableLoading"
                    :data="dataList"
                    :columns="tableColumns"
                    :row-key="rowKey"
                    :style="{ height: `${tableHeight}px` }"
                    :flex-height="true"
                    :scroll-x="1000"
                />
                    <n-drawer v-model:show="active" width="50%" :placement="placement">
                        <n-drawer-content title="Order Details">
                            <!-- Dynamic content rendering -->
                            <n-space vertical align="center">
                                <n-card title="Детали канала" style="width: 100%">
                                    <div>
                                        Название канала: {{ rowData.channel?.channel_name }}
                                        <n-divider />
                                        Описание: {{ rowData.channel?.description }}
                                        <n-divider />
                                        URL: {{ rowData.channel?.url }}
                                        <n-divider />
                                        Аватар: {{ rowData.channel?.avatar }}
                                        <n-divider />
                                        Тип: {{ rowData.channel?.type }}
                                        <n-divider />
                                        Язык: {{ rowData.channel?.language }}
                                        <n-divider />
                                        Источник подписчиков: {{ rowData.channel?.subscribers_source }}
                                        <n-divider />
                                        Цена формата 1: {{ rowData.channel?.format_one_price }}
                                        <n-divider />
                                        Цена формата 2: {{ rowData.channel?.format_two_price }}
                                        <n-divider />
                                        Цена формата 3: {{ rowData.channel?.format_three_price }}
                                        <n-divider />
                                        Цена без удаления: {{ rowData.channel?.no_deletion_price }}
                                        <n-divider />
                                        Цена репоста: {{ rowData.channel?.repost_price }}
                                        <n-divider />
                                        Скидка на повтор: {{ rowData.channel?.repeat_discount }}
                                        <n-divider />
                                        CPM: {{ rowData.channel?.cpm }}
                                        <n-divider />
                                        Процент мужчин: {{ rowData.channel?.male_percentage }}
                                        <n-divider />
                                        Счет: {{ rowData.channel?.score }}
                                        <n-divider />
                                        Рейтинг: {{ rowData.channel?.rating }}
                                        <n-divider />
                                        Количество лайков: {{ rowData.channel?.likes_count }}
                                        <n-divider />
                                        Количество просмотров: {{ rowData.channel?.views_count }}
                                        <n-divider />
                                        Статус: {{ rowData.channel?.status }}
                                        <n-divider />
                                        Дата создания канала: {{ rowData.channel?.channel_creation_date }}
                                    </div>
                                </n-card>
                                <n-card title="Детали формата" style="width: 100%">
                                    <div>
                                        Название формата: {{ rowData.format?.name }}
                                        <!-- Добавьте больше деталей формата здесь, если необходимо -->
                                    </div>
                                </n-card>
                                <n-card title="Детали пользователя" style="width: 100%">
                                    <div>
                                        Имя пользователя: {{ rowData.user?.username }}
                                          <n-divider />
                                        Email: {{ rowData.user?.email }}
                                          <n-divider />
                                        Номер телефона: {{ rowData.user?.mobile_number }}
                                          <n-divider />
                                        Telegram Username: {{ rowData.user?.telegram_username }}
                                          <n-divider />
                                        Telegram User ID: {{ rowData.user?.telegram_user_id }}
                                          <n-divider />
                                        VK ID: {{ rowData.user?.vk_id }}
                                          <n-divider />
                                        Баланс: {{ rowData.user?.balance }}
                                          <n-divider />
                                        Подтверждение email: {{ rowData.user?.email_verified_at }}
                                          <n-divider />
                                        Реферальный ID: {{ rowData.user?.referral_id }}
                                          <n-divider />
                                        Код двухфакторной аутентификации: {{ rowData.user?.two_factor_code }}
                                          <n-divider />
                                        Двухфакторная аутентификация истекает: {{ rowData.user?.two_factor_expires_at }}
                                          <n-divider />
                                        Двухфакторная аутентификация включена: {{ rowData.user?.two_factor_enabled }}
                                          <n-divider />
                                        Удалено: {{ rowData.user?.deleted_at }}
                                          <n-divider />
                                        Дата создания пользователя: {{ rowData.user?.created_at }}
                                          <n-divider />
                                        Дата обновления пользователя: {{ rowData.user?.updated_at }}
                                    </div>
                                </n-card>
                                <n-card title="Детали жалоб" style="width: 100%">
                                    <div>
                                        ID: {{ rowData.order_reports[0]?.order_id }}
                                          <n-divider />
                                        Описание: {{ rowData.order_reports[0]?.message }}
                                          <n-divider />
                                        Статус: {{ rowData.order_reports[0]?.status }}
                                    </div>
                                </n-card>
                                <n-button @click="closeDrawer">Закрыть</n-button>
                            </n-space>

                        </n-drawer-content>
                    </n-drawer>
                </div>
            </template>

            <template #footer>
                <TableFooter :pagination="pagination" />
            </template>

        </TableBody>
    </div>
</template>

<script>
import TableFooter from "@/Components/Admin/Tables/TableFooter.vue";
import {  renderTag } from '@/hooks/form'
import {
    usePagination,
    useRowKey,
    useTable,
    useTableColumn,
    useTableHeight,
} from "@/hooks/table";
import {
    NButton, NCard,
    NDataTable, NDivider, NDrawer, NDrawerContent,
    NInput,
    NSelect, NSpace,
    useMessage
} from 'naive-ui'
import { defineComponent, h, onMounted, ref, watch } from 'vue'
import DataForm from "@/Components/Admin/DataForm.js";
import TableHeader from "@/Components/Admin/Tables/TableHeader.vue";
import TableBody from "@/Components/Admin/Tables/TableBody.vue";
import { router } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
const conditionItems = [
    {
        key: "id",
        label: "id",
        value: ref(null),
        render: (formItem) => {
            return h(NInput, {
                value: formItem.value.value,
                onUpdateValue: (val) => {
                    formItem.value.value = val;
                },
            });
        },
    },
    {
        key: "description",
        label: "Описание",
        value: ref(null),
        render: (formItem) => {
            return h(NInput, {
                value: formItem.value.value,
                onUpdateValue: (val) => {
                    formItem.value.value = val;
                }
            });
        },
    },
    {
        key: "price",
        label: "Стоимость",
        value: ref(null),
        render: (formItem) => {
            return h(NInput, {
                value: formItem.value.value,
                onUpdateValue: (val) => {
                    formItem.value.value = val;
                }
            });
        },
    },
    {
        key: "status",
        label: "status",
        value: ref(null),
        optionItems: [
            {
                label: "принятый",
                value: "accepted",
            },
            {
                label: "отклоненный",
                value: "declined",
            },
            {
                label: "в ожидании",
                value: "pending",
            },
        ],
        render: (formItem) => {
            return h(NSelect, {
                options: formItem.optionItems,
                value: formItem.value.value,
                placeholder: "status",
                onUpdateValue: (val) => {
                    formItem.value.value = val;
                },
            });
        },
    }
];
export default defineComponent({
    name: "OrdersTableWithSearch",
    components: { NDivider, NSpace, NButton, NCard, NDrawer, NDrawerContent, NDataTable, TableBody, TableHeader, DataForm, TableFooter },
    setup() {
        const searchForm = ref(null);
        const pagination = usePagination(doRefresh);
        pagination.pageSize = 20;
        const table = useTable();
        const message = useMessage();
        const rowKey = useRowKey("id");

        const active = ref(false);
        const placement = ref('right');
        let rowData = ref({});
        const openDrawer = (data) => {
            rowData.value = data;
            active.value = true;
        };
        const closeDrawer = (data) => {
            active.value = false;
        };

        const tableColumns = useTableColumn(
            [
                {
                    fixed: 'left',
                    width: 60,
                    title: "ID",
                    key: "id"
                },
                {
                    title: "Описание",
                    key: "description",
                },
                {
                    title: "Стоимость",
                    key: "price",
                },
                {
                    title: "post_date",
                    key: "post_date",
                },
                {
                    title: "post_date_end",
                    key: "post_date_end",
                },
                {
                    title: "В ближайшее время",
                    key: "near_future",
                },
                {
                    title: "Статус",
                    key: "status",
                    render: (rowData) =>
                        renderTag(trans('messages.' + rowData?.status), {
                            type: 'info',
                            size: "small",
                        }),
                },
                {
                    title: "Настройки",
                    key: "actions",
                    fixed: 'right',
                    width: 240,
                    render: (rowData) => {
                        return h(
                            NButton,
                            {
                                onClick: () => openDrawer(rowData)
                            },
                            () => 'Подробнее'
                        )
                    },
                }
            ],
            {
                align: "center",
            },
        );
        function doRefresh() {
            let searchParams = searchForm.value?.generatorParams();
            axios
                .get(route('admin.api.orders.index'), {
                    params: {
                        page: pagination.page,
                        pageSize: pagination.pageSize,
                        ...searchParams,
                    },
                })
                .then((res) => {
                    table.handleSuccess({ data: res.data.data });
                    pagination.setTotalSize(res.data.total);
                })
                .catch(console.log);
        }
        function onSearch() {
            pagination.page = 1
            doRefresh();
            message.success(
                '!'
                    )
            ;
        }
        function onResetSearch() {
            searchForm.value?.reset();
            doRefresh();
        }
        onMounted(async () => {
            table.tableHeight.value = await useTableHeight();
            doRefresh();
        });
        return {
            ...table,
            rowKey,
            pagination,
            searchForm,
            tableColumns,
            conditionItems,
            onSearch,
            onResetSearch,
            active,
            placement,
            rowData,
            closeDrawer
        };
    },
});
</script>

<style lang="scss" scoped>
.avatar-container {
    position: relative;
    width: 30px;
    height: 30px;
    margin: 0 auto;
    vertical-align: middle;
    .avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }
    .avatar-vip {
        border: 2px solid #cece1e;
    }
    .vip {
        position: absolute;
        top: 0;
        right: -9px;
        width: 15px;
        transform: rotate(60deg);
    }
}
.gender-container {
    .gender-icon {
        width: 20px;
    }
}
</style>
