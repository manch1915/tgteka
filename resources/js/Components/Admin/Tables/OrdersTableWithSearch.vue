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
                        <n-drawer-content title="Детали заказа">
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
                                        Тип: {{ rowData.channel?.type }}
                                        <n-divider />
                                        Язык: {{ rowData.channel?.language }}
                                        <n-divider />
                                        Источник подписчиков: {{ rowData.channel?.subscribers_source }}
                                        <n-divider />
                                        Цена формата 1/24: {{ rowData.channel?.format_one_price }} &nbsp;руб.
                                        <n-divider />
                                        Цена формата 2/48: {{ rowData.channel?.format_two_price }} &nbsp;руб.
                                        <n-divider />
                                        Цена формата 3/72: {{ rowData.channel?.format_three_price }} &nbsp;руб.
                                        <n-divider />
                                        Цена без удаления: {{ rowData.channel?.no_deletion_price }} &nbsp;руб.
                                        <n-divider />
                                        Цена репоста: {{ rowData.channel?.repost_price }} &nbsp;руб.
                                        <n-divider />
                                        Скидка на повтор: {{ rowData.channel?.repeat_discount }}%
                                        <n-divider />
                                        CPM: {{ rowData.channel?.cpm }}
                                        <n-divider />
                                        Счет: {{ rowData.channel?.score }}
                                        <n-divider />
                                        Рейтинг: {{ rowData.channel?.rating }}
                                        <n-divider />
                                        Статус: {{ trans('messages.' + rowData.channel?.status)  }}
                                    </div>
                                </n-card>
                                <n-card title="" style="width: 100%">
                                    <div>
                                        Ближайшее время:
                                        <n-tag type="success" v-if="rowData.near_future">Да</n-tag>
                                        <n-tag type="error" v-else>Нет</n-tag>
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
                                        Баланс: {{ rowData.user?.balance }}
                                          <n-divider />
                                        Дата создания пользователя: {{ formatDate(rowData.user?.created_at) }}
                                    </div>
                                </n-card>
                                <n-card title="Детали жалоб" style="width: 100%">
                                    <div>
                                        ID: {{ rowData.order_reports[0]?.order_id }}
                                          <n-divider />
                                        Описание: {{ rowData.order_reports[0]?.message }}
                                          <n-divider />
                                        Статус: {{ trans('messages.' + rowData.order_reports[0]?.status) }}
                                    </div>
                                </n-card>
                                <n-card title="Изменение статуса заказа" style="width: 100%">
                                    <div>
                                        <n-select
                                            v-model:value="statusVal"
                                            :options="statusOptions"
                                            placeholder="Выберите статус"
                                        />
                                        <n-button @click="updateOrderStatus">Сохранить</n-button>
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
    NSelect, NSpace, NTag,
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
    methods: { trans },
    components: { NSelect, NTag, NDivider, NSpace, NButton, NCard, NDrawer, NDrawerContent, NDataTable, TableBody, TableHeader, DataForm, TableFooter },
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
        const statusVal = ref('')
        const statusOptions = [
            { label: 'Принятый', value: 'accepted' },
            { label: 'Отклоненный', value: 'declined' },
            { label: 'В ожидании', value: 'pending' },
        ];
        const updateOrderStatus = () => {
            axios.put(route('admin.api.orders.update', { id: rowData.value.id }), { status: statusVal.value })
                .then(() => {
                    message.success('Статус изменен');
                    closeDrawer();
                    doRefresh();
                })
                .catch(error => {
                    message.error('Ошибка при изменении статуса');
                    console.error(error);
                });
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
                    title: "Название формата",
                    key: "format_name",
                    render: (rowData) => rowData?.format?.name,
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
        function formatDate(dateString) {
            if (!dateString) return '';

            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const date = new Date(dateString);
            return date.toLocaleDateString('ru-RU', options).replace(',', '');
        }
        return {
            ...table,
            formatDate,
            statusVal,
            statusOptions,
            updateOrderStatus,
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
