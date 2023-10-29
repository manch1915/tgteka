<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Quill, QuillEditor} from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import "quill-emoji/dist/quill-emoji.css";
import { ref, watch} from "vue";

const props = defineProps({
    patternContent: [String, null],
    patternId: Number,
    patternMedia: [String, null]
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

let editorMethods = ref(null);
let htmlContent = ref('');
const quillInstance = ref(null);
let onEditorReady = (methods, quill) => {
    editorMethods.value = methods; // store editor methods
    quillInstance.value = quill;  // store the Quill instance
};

const content = ref(props.patternContent || '');

watch(content, () => {
    if(editorMethods.value) {
        htmlContent.value = editorMethods.value.root.innerHTML;
    }
}, { immediate: true, deep: true });

const htmlToDelta = (html) => {
    const div = document.createElement('div');
    div.setAttribute('id', 'htmlToDelta');
    div.innerHTML = `<div id="quillEditor" style="display:none">${html}</div>`;
    document.body.appendChild(div);
    const quill = new Quill('#quillEditor', {
        theme: 'snow',
    });
    const delta = quill.getContents();
    document.getElementById('htmlToDelta').remove();
    return delta;
}

content.value = htmlToDelta(content.value);
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
const uploadedImageUrl = ref(props.patternMedia || '/images/photo.png');

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
        <div class="mt-28">
            <div class="text-center">
                <div class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Мои шаблоны</div>
            </div>
            <div class="mt-16 mb-12">
                <div class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-10">Создание поста №
                     (название)</div>
            </div>
            <div class="blok p-8 rounded-tr-3xl rounded-bl-3xl rounded-br-3xl border-2 border-white backdrop-blur-3xl">
                <h2 class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed mb-5">Ваш пост</h2>
                <QuillEditor @ready="(methods, quill) => onEditorReady(methods, quill)" v-model:content="content" :options="options" theme="snow" class="text-violet-100"  placeholder="Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации.  Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации.Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации." />
                <div class="flex justify-between items-center py-6">
                    <div class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Шаблон сохраняется автоматически</div>
                    <div class="text-purple-600 text-sm font-normal font-['Poppins'] leading-tight">Очистить всё</div>
                </div>
                <hr>
                <div class="mediafiles grid grid-cols-2 mt-5">
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
                    <div class="grid grid-cols-5 gap-2 justify-end justify-items-end">
                        <div v-for="n in 10" class="w-24 h-24 bg-zinc-300 rounded-lg"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-24 text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Предпросмотр поста</div>
        <div class="before w-96 mt-6">
            <div class="before_container">
                <img :src="uploadedImageUrl" class="object-cover" alt="" />
                <div class="pt-2 text-violet-100 text-base font-normal font-['Inter'] leading-tight" v-html="htmlContent"></div>
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
