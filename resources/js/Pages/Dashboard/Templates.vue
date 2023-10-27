<script>
import { ref } from 'vue'
import { Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

export default {
    components: {
        AppLayout,
        Link
    },
    props: {
        patterns: Array
    },
    directives: {
        clickOutside: {
            beforeMount: (el, binding) => {
                el.clickOutsideEvent = event => {
                    // Check if clicked element is not the context menu or its descendants
                    if (el !== event.target && !el.contains(event.target)) {
                        binding.value(event);
                    }
                };
                document.body.addEventListener('click', el.clickOutsideEvent);
            },
            unmounted: (el) => {
                document.body.removeEventListener('click', el.clickOutsideEvent);
            },
        }
    },
    setup() {
        const isContextMenuOpen = ref(false)
        const contextMenuId = ref(null)

        const changeContext = (id) => {
            contextMenuId.value = id
            isContextMenuOpen.value = true
        };

        const handleFocusOut = event => {
            console.log(123)
            if (!event.target.matches(".context, .context *")) {
                isContextMenuOpen.value = false;
            }
        }

        return {
            isContextMenuOpen,
            contextMenuId,
            changeContext,
            handleFocusOut
        }
    }
}
</script>

<template>
    <AppLayout>
        <div class="mt-28">
            <div class="text-center">
                <div class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Мои шаблоны</div>
            </div>
            <template v-for="pattern in patterns" :key="pattern.id">
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
                    <div v-click-outside="handleFocusOut" v-show="isContextMenuOpen && contextMenuId === pattern.id" class="absolute context z-10">
                        <ul>
                            <li class="flex items-center gap-x-1 py-1"><img src="/images/document-text.svg" alt="ocument-text">Редактировать</li>
                            <li class="flex items-center gap-x-1 py-1"><img src="/images/edit-2.svg" alt="edit">Переименовать</li>
                            <li class="flex items-center gap-x-1 py-1"><img src="/images/group-menu.svg" alt="group-menu">Дублировать</li>
                            <li class="flex items-center gap-x-1 py-1"><img src="/images/trash.svg" alt="trash">Удалить</li>
                        </ul>
                    </div>
                    </Transition>
                </div>
            </template>
            <div class="flex flex-col gap-y-10 mt-32">
                <template v-if="patterns && patterns.length === 0">
                    <div class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">Шаблонов нет</div>
                    <div class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                        Вы еще не создали ни одного шаблона
                    </div>
                </template>
                <div>
                    <Link :href="route('adding-pattern')">
                        <button class="text-violet-100 px-6 py-4 bg-purple-600 rounded-full">Создать шаблон</button>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped lang="scss">
.data_container {
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
}

.border-color {
    border-color: rgb(23 37 84);
}

.data {
    background: #4E526A;
}
.context{
    border-radius: 20px;
    border: 3.5px solid #2611A5;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
    backdrop-filter: blur(21px);
    padding: 10px;
    right: -8%;
    top: 50%;
    ul{
        li{
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
</style>
