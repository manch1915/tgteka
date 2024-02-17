<script setup>
import {ref, onBeforeMount, onBeforeUnmount, onMounted} from 'vue';
import {Head, Link, router} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import axios from "axios";
import TailwindPagination from "laravel-vue-pagination/src/TailwindPagination.vue";
import {useMessage} from "naive-ui";

const isContextMenuOpen = ref(false);
const contextMenuId = ref(null);
const patterns = ref([])
const message = useMessage()

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
    router.visit(route('pattern.edit', patternID))
}


const duplicatePattern = async (patternIdToDuplicate) => {
    const {data} = await axios.post(`/pattern/${patternIdToDuplicate}/duplicate`);
    patterns.value.data.push(data);
};

const deletePattern = async (patternIdToDelete) => {
    try {
        await axios.delete(`/pattern/${patternIdToDelete}`);
        const indexToDelete = patterns.value.data.findIndex((pattern) => pattern.id === patternIdToDelete);
        if (indexToDelete !== -1) {
            patterns.value.data.splice(indexToDelete, 1);
        }
    } catch (error) {
        if (error.response && error.response.status === 409) {
            message.error(error.response.data.message);
        }
    }
};

const getPatterns = async (page = 1) => {
    const url = route('user-patterns-paginated') + `?page=${page}`
    await axios.get(url)
        .then(response => {
            patterns.value = response.data
        })
}
onMounted(() => getPatterns())
</script>

<template>
    <Head>
        <title>Мои шаблоны</title>
    </Head>
    <AppLayout>
        <div class="sm:mt-28 mt-10">
            <div :class="patterns.data && patterns.data.length === 0 ? 'text-center mb-12' : 'text-left mb-12'">
                <h1 class="sm:text-left text-center text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10">Мои шаблоны</h1>
            </div>
            <div class="grid gap-x-12">
                <div class="sm:px-0 px-2">
                    <transition-group name="fade" tag="div" mode="in-out">
                    <template v-if="patterns" v-for="(pattern, index) in patterns.data" :key="index">
                    <div class="relative">
                        <div @click.prevent="navigateToEditPattern(pattern.id)" class="cursor-pointer mb-5 data_container flex items-center justify-between origin-top-left rounded-tr-3xl rounded-bl-3xl rounded-br-3xl border border-color backdrop-blur-2xl px-4 py-3">
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
                                   {{ pattern.title }}
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
                    <div class="relative h-full sm:block flex justify-center">
                        <div class="sticky top-0 inline-block">
                            <div class="wrapper">
                                <div class="top-0 create-pattern flex flex-col gap-y-4 items-center justify-center">
                                    <p class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">
                                        Создайте новый <br/>шаблон прямо сейчас</p>
                                    <div>
                                        <Link :href="route('pattern.adding')">
                                            <button class="text-violet-100 px-6 py-4 bg-purple-600 rounded-full">Создать шаблон</button>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <template v-if="patterns.data && patterns.data.length === 0">
                <div class="flex sm:justify-start justify-center">
                    <div class="flex flex-col justify-center gap-y-10 sm:mt-32 mt-10">
                            <div class="sm:text-start text-center text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">Шаблонов нет</div>
                            <div class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                Вы еще не создали ни одного шаблона
                            </div>
                            <div>
                                <Link :href="route('pattern.adding')">
                                    <button style="box-shadow: 0 0 3px #b7b7b7" class="text-violet-100 sm:w-auto w-full px-6 py-4 bg-purple-600 rounded-full">Создать шаблон</button>
                                </Link>
                            </div>
                    </div>
                </div>
            </template>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">
$gradient-background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
$font-OpenSans: Open Sans;

.data_container {
    background: $gradient-background;
}

.data {
    background: #4E526A;
}

.context {
    border-radius: 20px;
    border: 3.5px solid #2611A5;
    background: $gradient-background;
    backdrop-filter: blur(21px);
    padding: 10px;
    right: -8%;
    top: 50%;
    ul {
        li {
            color: #EAE0FF;
            font-feature-settings: 'clig' off, 'liga' off;
            font-family: $font-OpenSans;
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 140%; /* 19.6px */
            cursor: pointer;
        }
    }
    @media screen and (max-width: 640px){
        right: 0;
    }
}

.v-enter-active,
.v-leave-active,
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.v-enter-from,
.v-leave-to,
.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.grid{
    grid-template-columns: 9fr 3fr;
    @media screen and (max-width: 640px){
        grid-template-columns: 1fr;
    }
}

.create-pattern{
    width: 280px;
    height: 368px;
    border-radius: 0 62px 62px 62px;
    border: 2px solid #FFF;
    background: $gradient-background;
    backdrop-filter: blur(13px);
}
</style>
<style>
$border-color: rgb(23 37 84);

.bg-blue-950{
    background: #171961;
}

.border-color {
    border-color: $border-color;
}

.wrapper{
    position: relative;
    &::before {
        content: "";
        position: absolute;
        left: 24px;
        right: 0;
        top: 0;
        bottom: -25px;
        border-radius: 0 40px 40px 40px;
        background: $gradient-background;
        backdrop-filter: blur(21px);
        transform: rotate(10deg);
        z-index: -1;
    }
}
</style>
