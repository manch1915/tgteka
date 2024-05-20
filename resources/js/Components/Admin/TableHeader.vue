<template>
    <div id="tableHeaderContainer" class="relative">
        <n-card
            :title="title"
            :content-style="{ padding: '0px' }"
            :bordered="false"
            header-style="font-size: 16px; padding: 10px; border-radius: 0"
        >
            <template #header-extra>
                <n-space>
                    <slot name="table-config"></slot>
                    <n-tooltip
                        class="ml-2 mr-2"
                        trigger="hover"
                        v-if="showFilter"
                    >
                        <template #trigger>
                            <n-button
                                type="warning"
                                size="tiny"
                                @click="showSearchContent = !showSearchContent"
                            >
                                <template #icon>
                                    <n-icon>
                                        <BaseIcon class="text-white" :path="FilterIcon" />
                                    </n-icon>
                                </template>
                                Фильтр
                            </n-button>
                        </template>
                        Развернуть фильтры
                    </n-tooltip>
                    <slot name="top-right"></slot>
                </n-space>
            </template>
        </n-card>
    </div>
    <n-drawer
        v-model:show="showSearchContent"
        placement="top"
        :to="target"
        :height="searchContentHeight"
    >
        <n-drawer-content
            body-content-style="overflow: hidden"
            title="Условия поиска"
            closable
            header-style="font-size: 16px; padding: 15px"
        >
            <template #default>
                <slot name="search-content"></slot>
            </template>
            <template #footer>
                <div class="flex justify-end">
                    <n-space>
                        <n-button
                            type="warning"
                            size="small"
                            @click="doResetSearch"
                            >Очистить фильтры</n-button
                        >
                        <n-button type="primary" size="small" @click="doSearch"
                            >Искать</n-button
                        >
                        <n-button
                            type="info"
                            size="small"
                            @click="showSearchContent = false"
                            >Закрыть</n-button
                        >
                    </n-space>
                </div>
            </template>
        </n-drawer-content>
    </n-drawer>
</template>

<script>
import { defineComponent, nextTick, onMounted, ref } from "vue";
import { mdiFunction as FilterIcon } from "@mdi/js";
import {
    NButton,
    NCard,
    NDrawer,
    NDrawerContent,
    NIcon,
    NSpace,
    NTooltip,
} from "naive-ui";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";

export default defineComponent({
    name: "TableHeader",
    components: {
        NDrawerContent,
        NDrawer,
        BaseIcon,
        NIcon,
        NButton,
        NTooltip,
        NSpace,
        NCard,
        FilterIcon,
    },
    props: {
        title: {
            type: String,
            default: "基本操作",
        },
        showFilter: {
            type: Boolean,
            default: true,
        },
        searchContentHeight: {
            type: String,
            default: "300px",
        },
    },
    emits: ["search", "reset-search"],
    setup(props, { emit }) {
        const showSearchContent = ref(false);
        const target = ref(null);
        onMounted(() => {
            nextTick(() => {
                target.value = document.getElementById("tableHeaderContainer");
            });
        });
        function doSearch() {
            showSearchContent.value = false;
            emit("search");
        }
        function doResetSearch() {
            emit("reset-search");
        }
        return {
            showSearchContent,
            target,
            doSearch,
            doResetSearch,
            FilterIcon
        };
    },
});
</script>
