<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Quill, QuillEditor} from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import "quill-emoji/dist/quill-emoji.css";
import { ref, watch} from "vue";


const props = defineProps({
    patternCount: Number,
    patternId: Number
})

const options = {
    modules: {
        toolbar: {
            container: [
                ['bold', 'italic', 'underline', 'emoji'],
                ['link'],
            ],
        }
    }
}

const content = ref('')
let editorMethods = ref(null);
let htmlContent = ref('');
let onEditorReady = (methods) => {
    editorMethods.value = methods; // store editor methods
};

watch(content, () => {
    if(editorMethods.value) {
        htmlContent.value = editorMethods.value.root.innerHTML;
    }
}, { immediate: true, deep: true });

const handleFileUpload = (event) => {
    file.value = event.target.files[0];

    if (!file) {
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        uploadedImageUrl.value = e.target.result;
    };
    reader.readAsDataURL(file.value);
};
const file = ref(null)
const uploadedImageUrl = ref('/images/photo.png');

let config = {
    headers: {
        'Content-Type': 'multipart/form-data'
    }
}
const patchPattern = async () => {
    let formData = new FormData();
    formData.append('_method', 'PATCH');
    if (htmlContent.value) {
        formData.append('body', htmlContent.value);
    }
    if (file.value) {
        formData.append('media', file.value);
    }
    console.log(formData)
    try {
        if (props.patternId) {
            const response = await axios.post(route('pattern.update', props.patternId), formData, config);
            console.log(response)
        }
    } catch (error) {
        console.error(error);
    }
};

let typingTimer;

watch([content, uploadedImageUrl], () => {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(patchPattern, 500);
}, { immediate: true, deep: true });
</script>

<template>
    <AppLayout>
        <div class="mt-8 sm:mt-28">
        <div class="text-center">
            <div class="text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10">Мои шаблоны</div>
        </div>
            <div class="mt-4 mb-12 sm:mt-16">
                <div class="text-violet-100 sm:text-2xl text-xl sm:text-left text-center ms:px-0 px-4 font-bold font-['Open Sans'] sm:leading-10 leading-7">Создание поста № {{ patternCount }} (название)</div>
            </div>
            <div class="rounded-tr-3xl rounded-br-3xl rounded-bl-3xl border-2 border-white p-8 backdrop-blur-3xl blok">
                <h2 class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed mb-5">Ваш пост</h2>
                <QuillEditor v-model:content="content" :options="options" theme="snow" class="text-violet-100" @ready="onEditorReady" placeholder="Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации.  Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации.Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации." />
                <div class="flex sm:flex-row flex-col gap-y-4 items-center justify-between py-6">
                    <div class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Шаблон сохраняется автоматически</div>
                    <div class="text-purple-600 text-sm font-normal font-['Poppins'] leading-tight">Очистить всё</div>
                </div>
                <hr>
                <div class="mt-5 grid sm:grid-cols-2 grid-cols-1 mediafiles">
                    <div class="flex flex-col gap-y-8">
                        <div class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Медиафайлы</div>
                        <div class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Прикрепите файл</div>
                        <div>
                            <label class="cursor-pointer px-6 py-2 bg-blue rounded-full shadow-inner border border-white border-opacity-10 text-violet-100 text-sm font-normal font-['Poppins'] leading-tight custom-file-upload">
                                <input type="file" class="hidden" accept="image/*" @change="handleFileUpload($event)" />
                                Загрузить файл
                            </label>
                        </div>
                        </div>
                    <div class="grid grid-cols-5 sm:pt-0 pt-8 sm:justify-end sm:justify-items-end gap-2">
                        <div v-for="n in 10" class="sm:h-24 sm:w-24 h-12 w-12 rounded-lg bg-zinc-300"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-24 text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Предпросмотр поста</div>
        <div class="sm:p-0 px-4">
            <div class="mt-6 sm:w-1/4 w-full before">
                <div class="before_container">
                    <img :src="uploadedImageUrl" class="object-cover" alt="" />
                    <div class="pt-2 text-violet-100 text-base font-normal font-['Inter'] leading-tight" v-html="htmlContent"></div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style lang="scss">
.ql-toolbar{
    border-radius: 20px 20px 0 0;
    background: #EAE0FF;
}
.ql-container{
    border-radius: 0 0 20px 20px;
    border: 1px solid #EAE0FF;
    padding-bottom: 100px;
    @media screen and (max-width: 640px){
        padding-bottom: 30rem;
    }
}
.ql-editor.ql-blank::before{
    color: #EAE0FF;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%; /* 20.8px */
}
.before{
    padding: 50px 40px;
    background: url('/images/background.jpg') center;
    background-size: cover;
    border-radius: 20px;
    .before_container{
        border-radius: 20px;
        background: #176073;
        padding: 20px;
    }
}
.blok{
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.40) 0%, rgba(81, 63, 255, 0.00) 100%);
}
</style>
