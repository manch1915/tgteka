<template>
    <AppLayout>
        <div class="mt-8 sm:mt-28">
            <div class="text-center">
                <slot name="title" />
            </div>
            <slot name="subtitle" />
            <div class="rounded-tr-3xl rounded-br-3xl rounded-bl-3xl border-2 border-white p-8 backdrop-blur-3xl blok">
                <h2 class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed mb-5"><slot name="post-title" /></h2>
                <slot name="editor" />
                <div class="flex flex-col items-center justify-between gap-y-4 py-6 sm:flex-row">
                    <div class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Шаблон сохраняется автоматически</div>
                    <slot name="clear-all" />
                </div>
                <hr>
                <div class="mt-5 grid grid-cols-1 mediafiles sm:grid-cols-2">
                    <slot name="file-upload" />
                </div>
            </div>
        </div>
        <div class="mt-24 text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Предпросмотр поста</div>
        <div class="px-4 sm:p-0">
            <div class="mt-6 w-full before sm:w-1/3">
                <div class="before_container">
                    <slot name="post-preview" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

</script>

<style lang="scss">
.before{
    box-shadow: 0 0 3px #9d9d9d;
}
.before_container{
    a{
        color: #0059ff;
        text-decoration: underline;
    }
}
</style>
