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
import {reactive} from "vue";
import BaseButton from "@/Components/Admin/BaseButton.vue";
import {useMessage} from "naive-ui";
import CardBox from "@/Components/Admin/CardBox.vue";
import TableTopics from "@/Components/Admin/TableTopics.vue";
import {useMainStore} from "@/stores/main.js";

const form = reactive({
    title: ''
})

const message = useMessage()
const mainStore = useMainStore()

const storeTopic = () => {
    axios.post(route('admin.api.topics.store'), form)
        .then(r => {
            message.info('Topic created')
            form.title = ''
            mainStore.fetchTopics()
        })
}

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Тематики" main/>
        <FormField label="Добавить тематику">
            <FormControl v-model="form.title" :icon="mdiCardText" />
        </FormField>
        <BaseButton @click.prevent="storeTopic" color="yellow" label="Добавить"/>
        <CardBox class="mb-6 mt-12" has-table>
            <TableTopics/>
        </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
