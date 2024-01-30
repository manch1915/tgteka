<script setup>
import {
     mdiCardText,
    mdiTableBorder,
} from "@mdi/js";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/Admin/SectionTitleLineWithButton.vue";
import FormField from "@/Components/Admin/FormField.vue";
import FormControl from "@/Components/Admin/FormControl.vue";
import {ref, onMounted} from "vue";
import BaseButton from "@/Components/Admin/BaseButton.vue";
import {useMessage} from "naive-ui";

const message = useMessage()
const settings = ref([]);

onMounted(async () => {
    const response = await axios.get(route('admin.api.settings.index'));
    settings.value = response.data;
});

const updateSetting = (setting) => {
    console.log(setting)
    axios.post(route('admin.api.settings.update', setting.id), {
        ...setting,
        _method: 'PUT',
    })
        .then(r => {
            message.info('Настройки обновлены')
        })
}

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Настройки" main/>
        <template v-for="(setting, index) in settings" :key="index">
            <FormField :label="setting.key">
                <FormControl v-model="setting.value" :icon="mdiCardText" />
                <BaseButton @click.prevent="updateSetting(setting)" color="yellow" label="Update"/>
            </FormField>
        </template>
    </SectionMain>
  </LayoutAuthenticated>
</template>
