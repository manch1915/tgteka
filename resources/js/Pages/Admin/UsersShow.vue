<script setup>
import {
    mdiCardText,
    mdiTableBorder,
} from "@mdi/js";
import SectionMain from "@/Components/Admin/SectionMain.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/Admin/SectionTitleLineWithButton.vue";
import {reactive, watchEffect} from "vue";
import FormField from "@/Components/Admin/FormField.vue";
import FormControl from "@/Components/Admin/FormControl.vue";
import {NDatePicker, useMessage} from "naive-ui";
import {router} from "@inertiajs/vue3";

const message = useMessage()

const props = defineProps({
    user: {
        type: Object,
        default: () => ({})
    }
})

const form = reactive({
    username: "",
    mobile_number: "",
    email: "",
    balance: 0,
});

watchEffect(() => {
    Object.assign(form, props.user);
});


const updateUser = () => {
    axios.patch(route('admin.api.users.update', props.user.id), form)
    message.success('Пользователь успешно обновлен')
}

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Каналы" main/>
        <!-- Channel Name -->
        <FormField label="Имя пользователя">
            <FormControl v-model="form.username" :icon="mdiCardText" />
        </FormField>

        <!-- Channel Link -->
        <FormField label="email">
            <FormControl v-model="form.email" :icon="mdiCardText" />
        </FormField>

        <!-- Description -->
        <FormField label="Номер телефона">
            <FormControl v-model="form.mobile_number" :icon="mdiCardText" />
        </FormField>

        <!-- Topic ID -->
        <FormField label="Баланс">
            <FormControl v-model="form.balance" :icon="mdiCardText" />
        </FormField>

        <!-- Update Channel Button -->
        <button class="p-3 bg-yellow-700" @click="updateUser">Сохранить</button>
    </SectionMain>
  </LayoutAuthenticated>
</template>
