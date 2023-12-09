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
                <div class="flex sm:flex-row flex-col gap-y-4 items-center justify-between py-6">
                    <div class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Шаблон сохраняется автоматически</div>
                    <slot name="clear-all" />
                </div>
                <hr>
                <div class="mt-5 grid sm:grid-cols-2 grid-cols-1 mediafiles">
                    <slot name="file-upload" />
                </div>
            </div>
        </div>
        <div class="mt-24 text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Предпросмотр поста</div>
        <div class="sm:p-0 px-4">
            <div class="mt-6 sm:w-1/4 w-full before">
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
.before_container{
    a{
        color: #0059ff;
        text-decoration: underline;
    }
}
</style>
