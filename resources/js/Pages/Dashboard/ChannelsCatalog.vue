<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SortButton from "@/Components/Dashboard/SortButton.vue";

const sortData = [
  'Рейтинг',
  'ER',
  'Просмотры',
  'Подписчики',
  'Цена',
  'CPМ'
]
</script>

<template>
    <AppLayout>
        <div class="text-center py-20">
          <h1 class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Каталог Telegram-каналов</h1>
        </div>
        <div class="catalog">
            <div class="filter float-left pr-10">
              <button class=" px-6 py-3.5 bg-purple-600 rounded-3xl justify-start items-start text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Дополнительный фильтр</button>
            </div>
            <div class="channels">
              <div class="total text-right text-violet-100 text-sm font-normal font-['Open Sans'] leading-normal">Всего каналов 63 448</div>
              <div class="filter_buttons flex gap-x-3">
                <SortButton v-for="title in sortData" :title="title"/>
              </div>

            </div>
        </div>
    </AppLayout>
</template>
