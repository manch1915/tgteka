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
    useMessage
} from 'naive-ui'
import { defineComponent, h, onMounted, ref } from "vue";
import DataForm from "@/Components/Admin/DataForm.js";
import TableHeader from "@/Components/Admin/TableHeader.vue";
import TableBody from "@/Components/Admin/TableBody.vue";
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
        key: "name",
        label: "name",
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
        key: "email",
        label: "email",
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
export default defineComponent({
    name: "CallbackTableWithSearch",
    components: { NDataTable, TableBody, TableHeader, DataForm, TableFooter },
    setup() {
        const searchForm = ref(null);
        const pagination = usePagination(doRefresh);
        pagination.pageSize = 20;
        const table = useTable();
        const message = useMessage();
        const rowKey = useRowKey("id");
        const tableColumns = useTableColumn(
            [
                {
                    title: "ID",
                    key: "id"
                },
                {
                    title: "Имя",
                    key: "name",
                },
                {
                    title: "email",
                    key: "email",
                },
                {
                    title: "created_at",
                    key: "created_at",
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
                            { value: 'declined', label: 'отклонить' },
                            { value: 'finished', label: 'принять' }
                        ],
                        route('admin.api.callbacks.update', rowData.id),
                        message
                    ),
                }
            ],
            {
                align: "center",
            },
        );
        function doRefresh() {
            let searchParams = searchForm.value?.generatorParams();
            axios
                .get(route('admin.api.callbacks.index'), {
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
