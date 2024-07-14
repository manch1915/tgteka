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
                </div>
            </template>

            <template #footer>
                <TableFooter :pagination="pagination" />
            </template>
        </TableBody>
    </div>
</template>

<script>
import TableFooter from "@/Components/Admin/TableFooter.vue";
import { renderCallbackButtons, renderTag } from '@/hooks/form'
import {
    usePagination,
    useRowKey,
    useTable,
    useTableColumn,
    useTableHeight,
} from "@/hooks/table";
import {
    NDataTable,
    NInput,
    NSelect,
    useMessage
} from 'naive-ui'
import { computed, defineComponent, h, onMounted, reactive, ref, toRefs } from 'vue'
import DataForm from "@/Components/Admin/DataForm.js";
import TableHeader from "@/Components/Admin/TableHeader.vue";
import TableBody from "@/Components/Admin/TableBody.vue";
import { trans } from 'laravel-vue-i18n'

export default defineComponent({
    name: "PayoutsTableWithSearch",
    components: { NDataTable, TableBody, TableHeader, DataForm, TableFooter },
    setup() {
        const searchForm = ref(null);
        const pagination = usePagination(doRefresh);
        pagination.pageSize = 20;
        const table = useTable();
        const message = useMessage();
        const rowKey = useRowKey("id");
        const conditionItems = [
            {
                key: "status",
                label: "Статус",
                value: ref(null),
                render: (formItem) => {
                    return h(NSelect, {
                        options: statusOptions.value,
                        onUpdateValue: (val) => {
                            formItem.value.value = val;
                        },
                    });
                },
            },
            {
                key: "id",
                label: "id",
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
                key: "transaction_id",
                label: "id транзакции",
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
                key: "username",
                label: "Имя пользователя",
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
                key: "service",
                label: "Услуга",
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
                key: "details",
                label: "Детали",
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
                key: "amount",
                label: "Сумма",
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
        ];
        const tableColumns = useTableColumn(
            [
                {
                    fixed: 'left',
                    width: 60,
                    title: "id",
                    key: "id"
                },
                {
                    title: "id транзакции",
                    key: "transaction_id",
                },
                {
                    title: "Имя пользователя",
                    key: "user.username",
                },
                {
                    title: "Услуга",
                    key: "service",
                },
                {
                    title: "Детали",
                    key: "details",
                },
                {
                    title: "Сумма",
                    key: "amount",
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
                    key: "null",
                    fixed: 'right',
                    width: 240,
                    render: (rowData) => renderCallbackButtons(
                        [
                            { value: 'in_progress', label: 'в прогрессе' },
                            { value: 'completed', label: 'завершить' },
                            { value: 'rejected', label: 'отклонить' }
                        ],
                        route('admin.api.payouts.update', rowData.id),
                        message
                    ),}
            ],
            {
                align: "center",
            },
        );

        const statusCounts = ref([]);

        const state = reactive({
            statuses: [
                {
                    value: 'pending',
                    label: 'в ожидании',
                },
                {
                    value: 'succeeded',
                    label: 'успешный',
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
        const fetchStatusCounts = () => {
            axios.get(route('admin.api.payout.count-statuses')) // updated route here
                .then(response => {
                    statusCounts.value = response.data;
                });
        }
        const statusOptions = computed(() => {
            return statuses.value.map(status => {
                const count = statusCounts.value[status.value] ? statusCounts.value[status.value].count : 0;
                return {...status, label: `${status.label} (${count})`};
            });
        });
        function doRefresh() {
            let searchParams = searchForm.value?.generatorParams();
            axios
                .get(route('admin.api.payouts.index'), {
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
            fetchStatusCounts();
            message.success(
                '!'
                    )
            ;
        }
        function onResetSearch() {
            searchForm.value?.reset();
            doRefresh();
            fetchStatusCounts();
        }
        onMounted(async () => {
            table.tableHeight.value = await useTableHeight();
            doRefresh();
            fetchStatusCounts();
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
