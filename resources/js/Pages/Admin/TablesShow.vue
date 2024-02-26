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
    channel: {
        type: Object,
        default: () => ({})
    }
})

const form = reactive({
    url: "",
    channel_name: "",
    description: "",
    slug: "",
    topic_id: 0,
    subscribers_source: "",
    format_one_price: 0,
    format_two_price: 0,
    format_three_price: 0,
    no_deletion_price: 0,
    repost_price: 0,
    repeat_discount: 0,
    male_percentage: "",
    score: 0,
    rating: 0,
    likes_count: 0,
    views_count: 0,
    channel_creation_date: null,
});

watchEffect(() => {
    Object.assign(form, props.channel);
});

const toggleChannel = (status) => {
    form.status = status
    axios.patch(route('admin.api.channels.update', props.channel.slug), form)
    router.visit(route('admin.channels'))
}

const updateChannel = () => {
    axios.patch(route('admin.api.channels.update', props.channel.slug), form)
    message.success('канал успешно обновлен')
}

</script>

<template>
  <LayoutAuthenticated>
    <SectionMain>
      <SectionTitleLineWithButton :icon="mdiTableBorder" title="Каналы" main/>
        <!-- Channel Name -->
        <FormField label="Название канала">
            <FormControl v-model="form.channel_name" :icon="mdiCardText" />
        </FormField>

        <!-- Channel Link -->
        <FormField label="URL">
            <FormControl v-model="form.url" :icon="mdiCardText" />
        </FormField>

        <!-- Description -->
        <FormField label="Описание">
            <FormControl v-model="form.description" :icon="mdiCardText" />
        </FormField>

        <!-- Topic ID -->
        <FormField label="Идентификатор темы">
            <FormControl v-model="form.topic_id" :icon="mdiCardText" />
        </FormField>

        <!-- Subscribers Source -->
        <FormField label="Источник подписчиков">
            <FormControl v-model="form.subscribers_source" :icon="mdiCardText" />
        </FormField>

        <!-- Format One Price -->
        <FormField label="Цена формата один">
            <FormControl v-model="form.format_one_price" :icon="mdiCardText" />
        </FormField>

        <!-- Format Two Price -->
        <FormField label="Цена формата два">
            <FormControl v-model="form.format_two_price" :icon="mdiCardText" />
        </FormField>

        <!-- Format Three Price -->
        <FormField label="Цена формата три">
            <FormControl v-model="form.format_three_price" :icon="mdiCardText" />
        </FormField>

        <!-- No Deletion Price -->
        <FormField label="Цена за отсутствие удаления">
            <FormControl v-model="form.no_deletion_price" :icon="mdiCardText" />
        </FormField>

        <!-- Repost Price -->
        <FormField label="Цена за репост">
            <FormControl v-model="form.repost_price" :icon="mdiCardText" />
        </FormField>

        <!-- Repeat Discount -->
        <FormField label="Скидка за повтор">
            <FormControl v-model="form.repeat_discount" :icon="mdiCardText" />
        </FormField>

        <!-- Male Percentage -->
        <FormField label="Процент мужчин">
            <FormControl v-model="form.male_percentage" :icon="mdiCardText" />
        </FormField>

        <!-- Score -->
        <FormField label="Оценка">
            <FormControl v-model="form.score" :icon="mdiCardText" />
        </FormField>

        <!-- Rating -->
        <FormField label="Рейтинг">
            <FormControl v-model="form.rating" :icon="mdiCardText" />
        </FormField>

        <!-- Likes Count -->
        <FormField label="Количество лайков">
            <FormControl v-model="form.likes_count" :icon="mdiCardText" />
        </FormField>

        <!-- Views Count -->
        <FormField label="Количество просмотров">
            <FormControl v-model="form.views_count" :icon="mdiCardText" />
        </FormField>

        <!-- Channel Creation Date -->
        <FormField label="Дата создания канала">
            <n-date-picker type="date" v-model="form.channel_creation_date" :icon="mdiCardText" />
        </FormField>
        <div class="flex flex-wrap gap-3">
            <button class="p-3 bg-green-700" v-if="props.channel.status === 'pending'" @click="toggleChannel('loading')">Принять канал</button>
            <button class="p-3 bg-red-700" v-if="props.channel.status === 'pending'" @click="toggleChannel('declined')">Отклонить канал</button>
        </div>


        <!-- Update Channel Button -->
        <button class="p-3 bg-yellow-700" v-if="props.channel.status === 'accepted' || props.channel.status === 'loading'" @click="updateChannel">Обновить канал</button>
    </SectionMain>
  </LayoutAuthenticated>
</template>
