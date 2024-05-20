<template>
    <n-card
        :bordered="false"
        :content-style="{ padding: 0 }"
        class="table-footer-container"
    >
        <div class="flex justify-center">
            <n-pagination
                :page="pagination.page"
                :page-size="pagination.pageSize"
                :page-count="pagination.pageCount"
                :show-size-picker="pagination.showSizePicker"
                :page-sizes="pagination.pageSizes"
                @update:page="onChange"
                @update:page-size="onPageSizeChange"
            />
            <n-button
                v-if="showRefresh"
                circle
                style="margin-left: 10px"
                size="small"
                type="primary"
                @click="refresh"
            >
                <template #icon>
                    <n-icon>
                        <BaseIcon class="text-white" :path="RefreshIcon" />
                    </n-icon>
                </template>
            </n-button>
        </div>
    </n-card>
</template>

<script>
import { defineComponent, toRef } from "vue";
import { mdiRefresh as RefreshIcon } from "@mdi/js";
import { NButton, NCard, NIcon, NPagination } from "naive-ui";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";

export default defineComponent({
    name: "TableFooter",
    components: { BaseIcon, NIcon, NButton, NCard, NPagination, RefreshIcon },
    props: {
        pagination: {
            type: Object,
            required: true,
        },
        showRefresh: {
            type: Boolean,
            default: true,
        },
    },
    setup(props) {
        const pagination = toRef(props, "pagination");

        function onChange(page) {
            pagination.value.page = page;
            pagination.value.onChange();
        }

        function onPageSizeChange(pageSize) {
            pagination.value.page = 1;  // Reset to page 1 when page size changes
            pagination.value.pageSize = pageSize;
            pagination.value.onPageSizeChange();
        }

        function refresh() {
            pagination.value.onChange();
        }

        return {
            onChange,
            onPageSizeChange,
            refresh,
            RefreshIcon,
        };
    },
});
</script>

<style lang="scss" scoped>
.table-footer-container {
    height: 45px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
}
</style>
