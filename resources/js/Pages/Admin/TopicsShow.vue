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
import {router} from "@inertiajs/vue3";

const props = defineProps({
    id: Number,
    title: String,
})

const form = reactive({
    title: props.title
})

const message = useMessage()

const updateTopic = () => {
    axios.patch(route('admin.api.topics.update', props.id), form)
        .then(r => {
            message.info('Тема обновлена')
            router.visit(route('admin.topics'))
        })
}

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Тематики" main/>
        <FormField label="Изменить тематику">
            <FormControl v-model="form.title" :icon="mdiCardText" />
        </FormField>
        <BaseButton @click.prevent="updateTopic" color="yellow" label="Изменить"/>
    </SectionMain>
  </LayoutAuthenticated>
</template>
