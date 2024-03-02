<template>
    <AppLayout>
        <div class="mt-8 sm:mt-28">
            <div class="text-center">
                <slot name="title"/>
            </div>
            <slot name="subtitle"/>
            <div class="rounded-tr-3xl rounded-br-3xl rounded-bl-3xl border-2 border-white p-8 backdrop-blur-3xl blok">
                <div class="grid grid-cols-1 gap-x-1.5 lg:grid-cols-2 lg:gap-x-2 xl:(grid-cols-3 gap-x-4)">
                    <div class="h-full">
                        <h2 class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed mb-5">
                            <slot name="post-title"/>
                        </h2>
                        <slot name="editor"/>
                    </div>
                    <div class="px-4 sm:p-0">
                        <div class="mt-6 w-full before">
                            <div class="before_container">
                                <slot name="post-preview"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-between gap-y-4 py-6 sm:flex-row">
                    <div class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Шаблон сохраняется
                        автоматически
                    </div>
                    <slot name="clear-all"/>
                </div>
                <hr>
                <div class="mt-5 grid grid-cols-1 mediafiles sm:grid-cols-2">
                    <slot name="file-upload"/>
                </div>
                <slot/>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

</script>

<style lang="scss">
.before {
    box-shadow: 0 0 3px #9d9d9d;
}

.before_container {
    a {
        color: #0059ff;
        text-decoration: underline;
    }
}
</style>
