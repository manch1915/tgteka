<script setup>
import { QuillEditor} from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import "quill-emoji/dist/quill-emoji.css";
import {computed, defineComponent, onUnmounted, ref, watch} from "vue";
import TemplateLayout from "@/Layouts/TemplateLayout.vue";
import {NImage, NImageGroup, useLoadingBar, useMessage} from "naive-ui";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import {mdiDelete} from "@mdi/js";
import { VueDraggableNext } from 'vue-draggable-next'
import {options, handleFileUpload, deleteImage, isEmptyEditor} from "@/utilities/templateUtilities.js"

const props = defineProps({
    patternCount: Number,
    patternId: Number
})



const draggable = defineComponent(VueDraggableNext)

const loading = useLoadingBar()

const content = ref('')
let editorMethods = ref(null);
let htmlContent = ref('');

const message = useMessage();

const isEmptyEditor = computed(() => {
    if (typeof content.value === 'object' && content.value.ops) {
        return content.value.ops.length === 1 && content.value.ops[0].insert === '\n';
    }
    return true;
});

let onEditorReady = (methods) => {
    editorMethods.value = methods; // store editor methods
};

watch(content, () => {
    if(editorMethods.value) {
        htmlContent.value = editorMethods.value.root.innerHTML;
    }
}, { immediate: true, deep: true });

const handleFileUpload = (event) => {
    const files = event.target.files;
    if (images.value.length + files.length > 10) {
        message.error("Вы не можете загрузить более 10 изображений.", {duration: 1000 * 10});
        return;
    }
    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        if (file.size <= 5 * 1024 * 1024) {
            images.value.push({displayImage: URL.createObjectURL(file), file: file});
        } else {
            message.error("Размер файла не должен превышать 5MB.", {duration: 1000 * 10});
        }
    }
};
const deleteImage = (index) => {
    images.value.splice(index, 1);
};
const file = ref(null)
const images = ref([])
const uploadedImageUrl = ref('/images/photo.png');

let config = {
    headers: {
        'Content-Type': 'multipart/form-data'
    }
}
const patchPattern = async () => {
    loading.start();
    let formData = new FormData();
    formData.append('_method', 'PATCH');

    if (htmlContent.value) {
        formData.append('body', htmlContent.value);
    }

    if (images.value.length > 0) {
        for (let i=0; i < images.value.length; i++) {
            formData.append('media[]', images.value[i].file);
        }
    }
    try {
        if (props.patternId) {
            const response = await axios.post(
                route('pattern.update', props.patternId),
                formData,
                config
            );
            loading.finish();
            console.log(response);
        }
    } catch (error) {
        console.error(error);
        loading.error();
    }
};

onUnmounted(() => {
    images.value.forEach(image => {
        URL.revokeObjectURL(image.displayImage);
    });
});

let typingTimer;

watch([content, images], () => {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(patchPattern, 500);
}, { immediate: true, deep: true });
</script>

<template>
    <TemplateLayout>
        <template #title>
            <div class="text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10">Мои шаблоны</div>
        </template>
        <template #subtitle>
            <div class="mt-4 mb-12 sm:mt-16">
                <div class="text-violet-100 sm:text-2xl text-xl sm:text-left text-center ms:px-0 px-4 font-bold font-['Open Sans'] sm:leading-10 leading-7">Создание поста № {{ patternCount }} (название)</div>
            </div>
        </template>
        <template #post-title>
            Ваш пост
        </template>
        <template #editor>
            <QuillEditor v-model:content="content" :options="options" theme="snow" :class="{'empty-editor': isEmptyEditor}"  class="text-violet-100 ql-container ql-snow" @ready="onEditorReady" placeholder="Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации.  Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации.Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации." />
        </template>
        <template #clear-all>
            <button class="text-purple-600 text-sm font-normal font-['Poppins'] leading-tight">Очистить всё</button>
        </template>
        <template #file-upload>
            <div class="flex flex-col gap-y-8">
                <div class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Медиафайлы</div>
                <div class="text-violet-100 text-sm font-normal font-['Poppins'] leading-tight">Прикрепите файл</div>
                <div>
                    <label class="cursor-pointer transition px-6 py-2 bg-blue-950 animation hover:bg-transparent rounded-full shadow-inner border border-white border-opacity-10 text-violet-100 text-sm font-normal font-['Poppins'] leading-tight custom-file-upload">
                        <input type="file" multiple class="hidden" accept="image/*,video/*" @change="handleFileUpload($event)" />
                        Загрузить файл
                    </label>
                </div>
            </div>
            <n-image-group>
                <draggable v-model="images" group="people" item-key="id" class="grid grid-cols-5 sm:pt-0 pt-8 sm:justify-end sm:justify-items-end gap-2">
                    <transition-group name="delete">
                        <div v-for="(image, index) in images" :key="'img-' + index" class="sm:h-24 sm:w-24 h-12 w-12 rounded-lg relative">
                            <template v-if="image.file && image.file.type.includes('image/')">
                                <n-image :src="image.displayImage" alt="" class="absolute top-0 left-0 object-cover w-full h-full"/>
                            </template>
                            <template v-else-if="image.file && image.file.type.includes('video/')">
                                <video :src="image.displayImage" autoplay loop muted class="absolute top-0 left-0 object-cover w-full h-full"></video>
                            </template>
                            <div @click="deleteImage(index)" class="cursor-pointer absolute bottom-0 right-0 rounded m-1 bg-gray-700 bg-opacity-80">
                                <BaseIcon class="p-1 text-white" :path="mdiDelete" />
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
                        <template v-if="image.file && image.file.type.includes('image/')">
                            <n-image :src="image.displayImage" alt="" class="w-full h-full" />
                        </template>
                        <template v-else-if="image.file && image.file.type.includes('video/')">
                            <video :src="image.displayImage" autoplay loop muted/>
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
    height: auto;
    transition: height 1s ease-out;
    @media screen and (max-width: 640px){
        padding-bottom: 30rem;
    }
}

@keyframes example {
    from {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(0.5);
        opacity: 0.5;
    }
    to {
        transform: scale(0);
        opacity: 0;
    }
}

.delete-enter-active, .delete-leave-active {
    transition: all 0.5s;
}

.delete-enter, .delete-leave-to {
    opacity: 1;
    transform: scale(1);
}

.delete-leave-active {
    animation: example 0.5s;
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
.break-words {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;  /* Non standard for webkit */
    white-space: pre-wrap; /* CSS3 */
}

.save__button{
    box-shadow: 0 0 4px #9d9d9d;
    &:hover{
        box-shadow: 0 0 10px #9d9d9d;
    }
}
</style>
