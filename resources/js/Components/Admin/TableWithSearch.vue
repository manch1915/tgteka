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
                <n-data-table
                    size="small"
                    :loading="tableLoading"
                    :data="dataList"
                    :columns="tableColumns"
                    :row-key="rowKey"
                    :style="{ height: `${tableHeight}px` }"
                    :flex-height="true"
                />
            </template>

            <template #footer>
                <TableFooter :pagination="pagination" />
            </template>
        </TableBody>
    </div>
</template>

<script>
import TableFooter from "@/Components/Admin/TableFooter.vue";
import { renderTag } from "@/hooks/form";
import {
    usePagination,
    useRowKey,
    useTable,
    useTableColumn,
    useTableHeight,
} from "@/hooks/table";
import {
    NAvatar, NButton,
    NCheckbox,
    NCheckboxGroup,
    NDataTable,
    NDatePicker,
    NInput,
    NSelect,
    NSpace,
    NTimePicker,
    useMessage
} from 'naive-ui'
import { defineComponent, h, onMounted, ref } from "vue";
import DataForm from "@/Components/Admin/DataForm.js";
import TableHeader from "@/Components/Admin/TableHeader.vue";
import TableBody from "@/Components/Admin/TableBody.vue";
import { router } from '@inertiajs/vue3'
const conditionItems = [
    {
        key: "channel_name",
        label: "Название канала",
        value: ref(null),
        render: (formItem) => {
            return h(NInput, {
                value: formItem.value.value,
                onUpdateValue: (val) => {
                    formItem.value.value = val;
                },
                placeholder: "Название канала",
            });
        },
    },
    {
        key: "url",
        label: "url",
        value: ref(null),
        render: (formItem) => {
            return h(NInput, {
                value: formItem.value.value,
                onUpdateValue: (val) => {
                    formItem.value.value = val;
                },
                placeholder: "url",
            });
        },
    },
    {
        key: "language",
        label: "Язык",
        value: ref(null),
        render: (formItem) => {
            return h(NInput, {
                value: formItem.value.value,
                onUpdateValue: (val) => {
                    formItem.value.value = val;
                },
                placeholder: "Русский",
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
                },
                placeholder: "Описание",
            });
        },
    },
    {
        key: "subscribers_source",
        label: "Источник подписчиков",
        value: ref(null),
        render: (formItem) => {
            return h(NInput, {
                value: formItem.value.value,
                onUpdateValue: (val) => {
                    formItem.value.value = val;
                },
                placeholder: "Источник подписчиков",
            });
        },
    },
    {
        key: "status",
        label: "Статус",
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
                label: "в прогрессе",
                value: "loading",
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
    },
];
export default defineComponent({
    name: "TableWithSearch",
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
                    title: "Название канала",
                    key: "channel_name",
                },
                {
                    title: "Avatar",
                    key: "avatar",
                    render: (rowData) => {
                        return h(
                            NAvatar,
                            {
                                circle: true,
                                size: "small",
                                src: rowData?.avatar
                            },
                        );
                    },
                },
                {
                    title: "url",
                    key: "url",
                },
                {
                    title: "Язык",
                    key: "language",
                },
                {
                    title: "Описание",
                    key: "description",
                },
                {
                    title: "Источник подписчиков",
                    key: "subscribers_source",
                },
                {
                    title: "Статус",
                    key: "status",
                    render: (rowData) =>
                        renderTag(rowData.status, {
                            type: 'info',
                            size: "small",
                        }),
                },
                {
                    title: "Настройки",
                    key: "null",
                    render: (rowData) => {
                        return h(
                            NButton,
                            {
                                onClick: () => { router.visit(route('admin.api.channels.edit', rowData.slug)) }
                            },
                            () => 'Перейти'
                        );
                    },
                },
            ],
            {
                align: "center",
            },
        );
        function doRefresh() {
            let searchParams = searchForm.value?.generatorParams();
            axios
                .get(route('admin.api.channels.index'), {
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
