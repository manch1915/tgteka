<script setup>
// Importing Modules
import { Quill, QuillEditor } from '@vueup/vue-quill'
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import "quill-emoji/dist/quill-emoji.css";
import { computed, defineComponent, ref, watch } from "vue";
import { NImage, NImageGroup, useMessage, useLoadingBar, NInput } from "naive-ui";
import { mdiDelete } from "@mdi/js";
import { VueDraggableNext } from 'vue-draggable-next'
import { pushModal } from "jenesius-vue-modal";
import { inputThemeOverrides } from "@/themeOverrides.js";
import TemplateLayout from "@/Layouts/TemplateLayout.vue";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import MainVideoPlayer from "@/Components/Dashboard/MainVideoPlayer.vue";
import {Head} from "@inertiajs/vue3";

// Properties Definition
const props = defineProps({
    patternContent: [String, null],
    patternId: Number,
    patternMedia: [Array, null],
    patternName: [String, null]
})

// Quill Options
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

// API and Helpers
const draggable = defineComponent(VueDraggableNext)
let config = { headers: { 'Content-Type': 'multipart/form-data' } }

// State Variables
let images = ref(props.patternMedia || []);
const message = useMessage();
const loading = useLoadingBar()
const editorMethods = ref(null);
const htmlContent = ref('');
const quillInstance = ref(null);
const content = ref(props.patternContent || '');
const file = ref(null)
const uploadedImageUrl = ref(props.patternMedia || '/images/photo.png');
const title = ref(props.patternName)


const onEditorReady = (methods, quill) => { editorMethods.value = methods; quillInstance.value = quill; };
const isEmptyEditor = computed(() => !!(typeof content.value === 'object' && content.value.ops && content.value.ops.length === 1 && content.value.ops[0].insert === '\n'));

const htmlToDelta = function(html) {
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
};
const handleFileUpload = function(event) {
    const files = Array.from(event.target.files);

    const isGifExist = images.value.some(image => image.file && image.file.type === 'image/gif');
    const gifFiles = files.filter(file => file.type === 'image/gif');
    const isGifUpload = gifFiles.length > 0;

    if (isGifUpload) {
        if (gifFiles.length > 1 || files.length > 1) {
            message.error("Вы можете загрузить только один GIF-файл без каких-либо других типов файлов.", {duration: 1000 * 10});
            return;
        }
    } else if (images.value.length + files.length > 10) {
        message.error("Русский: Вы не можете загрузить более 10 файлов.", {duration: 1000 * 10});
        return;
    }

    if (isGifUpload && images.value.length > 0) {
        message.error("Вы не можете загрузить GIF вместе с другими изображениями / видео. Очистите все и повторите попытку.", {duration: 1000 * 10});
        return;
    }

    if (isGifExist && files.length > 0) {
        message.error("Вы не можете загрузить другое изображение / видео вместе с GIF-файлом. Очистите все и повторите попытку.", {duration: 1000 * 10});
        return;
    }

    if (!isGifUpload && (images.value.length + files.length > 10)) {
        message.error("Вы не можете загрузить более 10 изображений / видео.", {duration: 1000 * 10});
        return;
    }

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        reader.onload = () => {
            images.value.push({url: URL.createObjectURL(file), file: file});
        };
        reader.readAsDataURL(file);
    }
};
const clearAll = function() {
    htmlContent.value = ''
    uploadedImageUrl.value = '/images/photo.png'
    file.value = null
    editorMethods.value.setContents([])
};
const deleteImage = function(index) {
    images.value.splice(index, 1);
};
const patchPattern = async function() {
    const { patternId } = props;

    if (!patternId) {
        return;
    }

    loading.start();

    let formData = new FormData();
    formData.append('_method', 'PATCH');
    formData.append('title', title.value);

    if (htmlContent.value) {
        formData.append('body', htmlContent.value);
    }

    images.value.forEach(image => formData.append('media[]', image.file || image.url));

    try {
        const response = await axios.post(route('pattern.update', patternId), formData, config);
        loading.finish();
        console.log(response);
    } catch (error) {
        loading.error();
        console.error(error);
    } finally {
        loading.finish();
    }
};
const openVideoPlayer = function(data) {
    pushModal(MainVideoPlayer, {url: data.url, image: data.image})
};

// Watches
watch(content, () => {
    if (editorMethods.value) {
        htmlContent.value = editorMethods.value.root.innerHTML;
    }
}, { immediate: true, deep: true });

htmlContent.value = content.value;
content.value = htmlToDelta(content.value);
</script>

<template>
    <Head>
        <title>Редактирование поста</title>
    </Head>
    <TemplateLayout>
        <template #title>
            <div class="text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10">Мои шаблоны</div>
        </template>
        <template #subtitle>
            <div class="mt-4 mb-12 sm:mt-16">
                <div class="text-violet-100 sm:text-2xl text-xl sm:text-left text-center ms:px-0 px-4 font-bold font-['Open Sans'] sm:leading-10 leading-7">Редактирование поста № {{ patternId }} {{patternName}}</div>
            </div>
        </template>
        <template #post-title>
            Ваш пост
        </template>
        <template #editor>
            <n-input :theme-overrides="inputThemeOverrides" class="mb-4" v-model:value="title"/>
            <QuillEditor @ready="(methods, quill) => onEditorReady(methods, quill)" v-model:content="content" :options="options" theme="snow" :class="{'empty-editor': isEmptyEditor}" class="text-violet-100 ql-container ql-snow"  placeholder="Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации.  Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации.Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации." />
        </template>
        <template #clear-all>
            <button @click.prevent="clearAll" class="text-purple-600 text-sm font-normal font-['Poppins'] leading-tight">Очистить всё</button>
        </template>
        <template #file-upload>
            <div class="flex flex-col gap-y-8">
                <div class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Медиафайлы</div>
                <div class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Прикрепите файл</div>
                <div>
                    <label class="cursor-pointer px-6 py-2 bg-blue-950 transition hover:bg-transparent rounded-full shadow-inner border border-white border-opacity-10 text-violet-100 text-sm font-normal font-['Poppins'] leading-tight custom-file-upload">
                        <input type="file" multiple class="hidden" accept="image/png, image/jpeg, image/jpg, image/gif, video/mp4" @change="handleFileUpload($event)" />
                        Загрузить файл
                    </label>
                </div>
            </div>
            <n-image-group>
                <draggable v-model="images" group="people" item-key="id" class="grid grid-cols-5 sm:pt-0 pt-8 sm:justify-end sm:justify-items-end gap-2">
                    <transition-group name="delete" >
                        <div v-for="(image, index) in images" :key="'img-' + index" class=" sm:h-24 sm:w-24 h-12 w-12 rounded-lg relative">
                            <template v-if="image.url && ['.mp4', '.ogg', '.webm'].some(ext => image.url.includes(ext))">
                                <img  @click.prevent="openVideoPlayer(image)"  v-if="['.mp4', '.ogg', '.webm'].some(ext => image.url.includes(ext))" :src="image.thumbnail_path" alt="thumbnail_path"  class="cursor-pointer absolute top-0 left-0 object-cover w-full h-full"/>
                            </template>
                            <template v-else-if="image.file && image.file.type.includes('video/')">
                                <video :src="image.url" autoplay loop muted class="absolute top-0 left-0 object-cover w-full h-full"></video>
                            </template>
                            <template v-else>
                                <n-image :src="image.url" alt="" class="absolute top-0 left-0 object-cover w-full h-full" />
                            </template>
                            <div @click="deleteImage(index)" class="cursor-pointer absolute bottom-0 right-0 rounded m-1 bg-gray-700 bg-opacity-80">
                                <BaseIcon class="p-1 text-white" :path="mdiDelete"/>
                            </div>
                        </div>
                    </transition-group>
                </draggable>
            </n-image-group>
        </template>
        <template #post-preview>
            <n-image-group v-if="images.length">
                <transition-group name="fade" tag="div" :class="[images.length <= 2 ? 'grid-cols-1' : '',
              images.length === 3 ? 'grid-cols-1 sm:grid-cols-2' : '',
              images.length >= 4 && images.length <= 6 ? 'grid-cols-2' : '',
              images.length > 6 ? 'grid-cols-3' : '']"
                                  class="grid gap-2">
                    <div v-for="(image, index) in images" :key="'img-' + index" class="bg-center bg-cover">
                            <template v-if="image.file">
                                <n-image v-if="image.file.type.includes('image/')" :src="image.url" alt="" class="w-full h-full" />
                                <video v-else-if="image.file.type.includes('video/')" :src="image.url" loop muted class="w-full h-full" />
                            </template>
                            <template v-else-if="image.url">
                                <img class="cursor-pointer" @click.prevent="openVideoPlayer(image)"  v-if="['.mp4', '.ogg', '.webm'].some(ext => image.url.includes(ext))" :src="image.thumbnail_path" alt=""/>
                                <n-image v-else :src="image.url" alt="" class="w-full h-full" />
                            </template>
                    </div>
                </transition-group>
            </n-image-group>
            <div v-else>
                <img :src="uploadedImageUrl" class="object-cover" alt="" />
            </div>
                <div class="break-words pt-2 text-violet-100 text-base font-normal font-['Inter'] leading-tight" v-html="htmlContent"></div>
        </template>
        <div class="mt-4 flex justify-center">
            <button @click.prevent="patchPattern" class="save__button text-violet-100 px-6 py-2 transition bg-purple-600 hover:bg-purple-800 rounded-full text-lg">Сохранить</button>
        </div>
    </TemplateLayout>

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
    height: auto;
    transition: height 1s ease-out;
    @media screen and (max-width: 640px){
        padding-bottom: 30rem;
    }
}

.ql-container.empty-editor {
    height: 200px;
    transition: height 1s ease-out;
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
.save__button{
    box-shadow: 0 0 4px #9d9d9d;
    &:hover{
        box-shadow: 0 0 10px #9d9d9d;
    }
}
</style>
