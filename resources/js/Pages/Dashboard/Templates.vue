<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Link} from "@inertiajs/vue3"
</script>

<template>
    <AppLayout>
        <div class="mt-28">
            <div class="text-center">
                <div class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Мои шаблоны</div>
            </div>
            <div class="flex flex-col gap-y-10 mt-32">
                <div class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">Шаблонов нет</div>
                <div class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                    Вы еще не создали ни одного шаблона
                </div>
                <div>
                    <Link :href="route('adding-pattern')"><button class="text-violet-100 px-6 py-4 bg-purple-600 rounded-full">Создать шаблон</button></Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">

</style>
