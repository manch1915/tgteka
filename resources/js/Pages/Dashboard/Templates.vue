<script setup>
import {ref, reactive, onBeforeMount, onBeforeUnmount, onMounted} from 'vue';
import {Link, router} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";


const isContextMenuOpen = ref(false);
const contextMenuId = ref(null);

const patterns = ref([])

const handleOutsideClick = (event) => {
    if (!event.target.matches(".context, .context *")) {
        isContextMenuOpen.value = false;
    }
};

onBeforeMount(() => {
    window.addEventListener('click', handleOutsideClick);
});

onBeforeUnmount(() => {
    window.removeEventListener('click', handleOutsideClick);
});
const changeContext = (id) => {
    contextMenuId.value = id;
    isContextMenuOpen.value = true;
};

const navigateToEditPattern = (patternID) => {
    router.visit(route('edit-pattern', patternID))
}

const renamePattern = async (patternTitleToRename) => {
    await axios.patch(`/pattern/${patternTitleToRename}/rename`, {title: patternTitleToRename});
};

const duplicatePattern = async (patternIdToDuplicate) => {
    const {data} = await axios.post(`/pattern/${patternIdToDuplicate}/duplicate`);
    patterns.value.data.push(data);
};

const deletePattern = async (patternIdToDelete) => {
    await axios.delete(`/pattern/${patternIdToDelete}`);
    const indexToDelete = patterns.value.data.findIndex((pattern) => pattern.id === patternIdToDelete);
    if (indexToDelete !== -1) {
        patterns.value.data.splice(indexToDelete, 1);
    }
};

const getPatterns = async (page = 1) => {
    const url = route('patterns.get') + `?page=${page}`
    await axios.get(url)
        .then(response => {
            patterns.value = response.data
        })
}
onMounted(() => getPatterns())
</script>

<template>
    <AppLayout>
        <div class="mt-28">
            <div :class="patterns.data && patterns.data.length === 0 ? 'text-center mb-12' : 'text-left mb-12'">
                <div class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Мои шаблоны</div>
            </div>
            <div class="grid gap-x-12">
                <div>
                    <transition-group name="fade" tag="div" mode="in-out">
                    <template v-if="patterns" v-for="(pattern, index) in patterns.data" :key="index">
                    <div class="relative">
                        <div class="mb-5 data_container flex items-center justify-between origin-top-left rounded-tr-3xl rounded-bl-3xl rounded-br-3xl border border-color backdrop-blur-2xl px-4 py-3">
                            <div class="flex items-center">
                                <div class="data shadow px-2.5 py-1 rounded-lg flex gap-x-1 items-center">
                                    <div>
                                        <img src="/images/calendar.svg" alt="">
                                    </div>
                                    <p class="text-violet-100 text-sm font-normal font-['Open Sans']">
                                        {{ pattern.localized_created_at }}
                                    </p>
                                </div>
                                <h3 class="pl-12 text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">
                                    Название шаблона
                                </h3>
                            </div>
                            <div @click.stop="changeContext(pattern.id)" class="cursor-pointer">
                                <img src="/images/menu.svg" alt="menu">
                            </div>
                        </div>
                        <Transition>
                            <div v-show="isContextMenuOpen && contextMenuId === pattern.id"
                                 class="absolute context z-10">
                                <ul>
                                    <li @click.prevent="navigateToEditPattern(pattern.id)" class="flex items-center gap-x-1 py-1">
                                        <img src="/images/document-text.svg" alt="ocument-text">Редактировать
                                    </li>
                                    <li class="flex items-center gap-x-1 py-1"><img src="/images/edit-2.svg" alt="edit">Переименовать
                                    </li>
                                    <li @click.prevent="duplicatePattern(pattern.id)"
                                        class="flex items-center gap-x-1 py-1"><img src="/images/group-menu.svg"
                                                                                    alt="group-menu">Дублировать
                                    </li>
                                    <li @click.prevent="deletePattern(pattern.id)"
                                        class="flex items-center gap-x-1 py-1"><img src="/images/trash.svg" alt="trash">Удалить
                                    </li>
                                </ul>
                            </div>
                        </Transition>
                    </div>
                </template>
                    </transition-group>
                    <div class="flex justify-center">
<!--                        TODO pakazat ishyo-->
                    <TailwindPagination :limit="3" :active-classes="['bg-blue-950', 'rounded-full', 'shadow-inner', 'border', 'border-white', 'border-opacity-10', 'text-white', 'text-base', 'font-bold', 'font-[\'Open Sans\']', 'leading-tight']" :itemClasses="['border-none', 'text-violet-100', 'text-base', 'font-normal', 'font-[\'Inter\']', 'leading-normal',]" @pagination-change-page="getPatterns" :data="patterns" >
                        <template v-slot:prev-nav>
                            <p class="text-center text-violet-100 text-base font-normal font-['Inter'] leading-normal">Назад</p>
                        </template>
                        <template v-slot:next-nav>
                            <p class="text-center text-violet-100 text-base font-semibold font-['Inter'] leading-snug">Вперёд</p>
                        </template>
                    </TailwindPagination>
                    </div>
                </div>
                <template v-if="patterns.data && patterns.data.length !== 0">
                <div class="relative h-full">
                    <div class="sticky top-0 create-pattern flex flex-col gap-y-4 items-center justify-center">
                        <p class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Создайте новый <br/>шаблон прямо сейчас</p>
                        <div>
                            <Link :href="route('adding-pattern')">
                                <button class="text-violet-100 px-6 py-4 bg-purple-600 rounded-full">Создать шаблон</button>
                            </Link>
                        </div>
                    </div>
                </div>
                </template>
            </div>
            <template v-if="patterns.data && patterns.data.length === 0">
                <div class="flex">
                    <div class="flex flex-col justify-center gap-y-10 mt-32">
                            <div class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">Шаблонов нет</div>
                            <div class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Вы еще не создали ни одного шаблона
                            </div>
                            <div>
                                <Link :href="route('adding-pattern')">
                                    <button class="text-violet-100 px-6 py-4 bg-purple-600 rounded-full">Создать шаблон</button>
                                </Link>
                            </div>
                    </div>
                </div>
            </template>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">
.data_container {
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
}

.data {
    background: #4E526A;
}

.context {
    border-radius: 20px;
    border: 3.5px solid #2611A5;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
    backdrop-filter: blur(21px);
    padding: 10px;
    right: -8%;
    top: 50%;

    ul {
        li {
            color: #EAE0FF;
            font-feature-settings: 'clig' off, 'liga' off;
            font-family: Open Sans;
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 140%; /* 19.6px */
            cursor: pointer;
        }
    }
}

.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
}
.grid{
    grid-template-columns: 9fr 3fr;
}
.create-pattern{
    width: 280px;
    height: 368px;
    border-radius: 0 62px 62px 62px;
    //TODO gridient border
    border: 2px solid #FFF;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.40) 0%, rgba(81, 63, 255, 0.00) 100%);
    backdrop-filter: blur(13px);
}
</style>
<style>
.bg-blue-950{
    background: #171961;
}
.border-color {
    border-color: rgb(23 37 84);
}
</style>
