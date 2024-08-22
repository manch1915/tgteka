<script setup>
import {
    mdiTableBorder,
} from "@mdi/js";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/Admin/SectionTitleLineWithButton.vue";
import {NButton, NTable} from "naive-ui";

const props = defineProps({
    report: Object
})

let accept = async () => {
    try {
        await axios.patch(route('admin.api.reports.update', props.report.data[0].report.id), {
            status: 'accepted'
        });

    } catch (error) {
        console.error(error);
    }
};

let decline = async () => {
    try {
        await axios.patch(route('admin.api.reports.update', props.report.data[0].report.id), {
            status: 'declined'
        });

    } catch (error) {
        console.error(error);
    }
};
</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Жалоба" main/>
        <n-table :bordered="false" :single-line="false">
            <thead>
            <tr>
                <th>Key</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(key,value) in report.data[0].report" :key="key">
                <td>{{ key }}</td>
                <td>{{ value }}</td>
            </tr>
            </tbody>
        </n-table>
        <n-table :bordered="false" :single-line="false">
            <thead>
            <tr>
                <th>Key</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(value, key) in report.data[0].order" :key="key">
                <td>{{ key }}</td>
                <td v-if="typeof value === 'object' && value !== null">
                    <n-table :bordered="false" :single-line="false">
                        <thead>
                        <tr>
                            <th>SubKey</th>
                            <th>SubValue</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(subValue, subKey) in value" :key="`${key}-${subKey}`">
                            <td>{{ subKey }}</td>
                            <td>{{ subValue }}</td>
                        </tr>
                        </tbody>
                    </n-table>
                </td>
                <td v-else>{{ value }}</td>
            </tr>
            </tbody>
        </n-table>
        <n-button @click="accept">одобрить жалобу</n-button>
        <n-button @click="decline">отменить жалобу</n-button>
    </SectionMain>

  </LayoutAuthenticated>
</template>
