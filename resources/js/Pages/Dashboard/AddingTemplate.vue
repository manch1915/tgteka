<script setup>
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import "quill-emoji/dist/quill-emoji.css";
import { computed, defineComponent, onUnmounted, ref, watch } from "vue";
import {
    NImageGroup,
    NImage,
    NInput,
    useLoadingBar,
    useMessage,
} from "naive-ui";
import { mdiDelete } from "@mdi/js";
import { VueDraggableNext } from "vue-draggable-next";
import TemplateLayout from "@/Layouts/TemplateLayout.vue";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import { options } from "@/utilities/templateUtilities.js";
import { inputThemeOverrides } from "@/themeOverrides.js";
import { Head } from "@inertiajs/vue3";
import { Title } from "chart.js";

const props = defineProps({
    patternCount: Number,
    patternId: Number,
    title: String,
});

let typingTimer;

const draggable = defineComponent(VueDraggableNext);

const loading = useLoadingBar();
const message = useMessage();

const content = ref("");
let editorMethods = ref(null);
let htmlContent = ref("");
let images = ref([]);
let file = ref(null);
let config = {
    headers: {
        "Content-Type": "multipart/form-data",
    },
};
const uploadedImageUrl = ref("/images/photo.png");
const title = ref(props.title);

let onEditorReady = (methods) => {
    editorMethods.value = methods; // store editor methods
};

// Functions and Computed Properties
const isEmptyEditor = computed(() => {
    if (content.value === "") {
        return true;
    }

    const ops = content.value.ops;

    if (ops && ops.length === 1 && ops[0].insert === "\n") {
        return true;
    }

    return false;
});

const handleFileUpload = function (event) {
    const files = Array.from(event.target.files);

    const isGifExist = images.value.some(
        (image) => image.file && image.file.type === "image/gif"
    );
    const gifFiles = files.filter((file) => file.type === "image/gif");
    const isGifUpload = gifFiles.length > 0;

    if (isGifUpload) {
        if (gifFiles.length > 1 || files.length > 1) {
            message.error(
                "Вы можете загрузить только один GIF-файл без каких-либо других типов файлов.",
                { duration: 1000 * 10 }
            );
            return;
        }
    } else if (images.value.length + files.length > 10) {
        message.error("Русский: Вы не можете загрузить более 10 файлов.", {
            duration: 1000 * 10,
        });
        return;
    }

    if (isGifUpload && images.value.length > 0) {
        message.error(
            "Вы не можете загрузить GIF вместе с другими изображениями / видео. Очистите все и повторите попытку.",
            { duration: 1000 * 10 }
        );
        return;
    }

    if (isGifExist && files.length > 0) {
        message.error(
            "Вы не можете загрузить другое изображение / видео вместе с GIF-файлом. Очистите все и повторите попытку.",
            { duration: 1000 * 10 }
        );
        return;
    }

    if (!isGifUpload && images.value.length + files.length > 10) {
        message.error("Вы не можете загрузить более 10 изображений / видео.");
        return;
    }
    const duplicateFiles = files.filter((file) => {
        return images.value.some(
            (image) => image.file && image.file.name === file.name
        );
    });

    if (duplicateFiles.length > 0) {
        message.error("Вы не можете загрузить дубликаты файлов.", {
            duration: 1000 * 10
        });
        return;
    }
    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        if (file.size <= 60 * 1024 * 1024) {
            images.value.push({
                displayImage: URL.createObjectURL(file),
                file: file,
            });
        } else {
            message.error("Размер файла не должен превышать 60MB.");
        }
    }
};
const deleteImage = function (index) {
    images.value.splice(index, 1);
};
const patchPattern = async function () {
    const { patternId } = props;

    if (!patternId) {
        return;
    }

    loading.start();

    let formData = new FormData();
    formData.append("_method", "PATCH");
    formData.append("title", title.value);

    if (htmlContent.value) {
        formData.append("body", htmlContent.value);
    }

    images.value.forEach((image) => formData.append("media[]", image.file));

    try {
        const response = await axios.post(
            route("pattern.update", patternId),
            formData,
            config
        );
        message.success("Шаблон успешно сохранен!");
        loading.finish();
    } catch (error) {
        loading.error();
    } finally {
        loading.finish();
    }
};

const clearAll = function () {
    htmlContent.value = "";
    uploadedImageUrl.value = "/images/photo.png";
    file.value = null;
    editorMethods.value.setContents([]);
    images.value = [];
};

// Watches
watch(
    content,
    () => {
        if (editorMethods.value) {
            htmlContent.value = editorMethods.value.root.innerHTML;
        }
    },
    { immediate: true, deep: true }
);

watch(
    [content, images, title],
    () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(patchPattern, 1300);
    },
    { immediate: true, deep: true }
);

// Cleanup on Unmount
onUnmounted(() => {
    images.value.forEach((image) => {
        URL.revokeObjectURL(image.displayImage);
    });
});
</script>

<template>
    <Head>
        <title>Мои шаблоны</title>
    </Head>

    <TemplateLayout>
        <template #title>
            <div
                class="text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10"
            >
                Мои шаблоны
            </div>
        </template>
        <template #subtitle>
            <div class="mt-4 mb-12 sm:mt-16">
                <div
                    class="text-violet-100 sm:text-2xl text-xl sm:text-left text-center ms:px-0 px-4 font-bold font-['Open Sans'] sm:leading-10 leading-7"
                >
                    Создание поста
                </div>
            </div>
        </template>
        <template #post-title> Ваш пост </template>
        <template #editor>
            <n-input
                :theme-overrides="inputThemeOverrides"
                class="mb-4"
                v-model:value="title"
            />
            <QuillEditor
                v-model:content="content"
                :options="options"
                theme="snow"
                :class="{ 'empty-editor': isEmptyEditor }"
                class="text-violet-100 ql-container ql-snow"
                @ready="onEditorReady"
                placeholder="Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации.  Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации.Детально укажите методы продвижения вашего канала. Укажите ссылки, если подписчики пришли с вашего аккаунта в Instagram, Facebook, YouTube, TikTok и т.д. — этоповысит шансы успешной модерации."
            />
        </template>
        <template #clear-all>
            <button
                @click.prevent="clearAll"
                class="text-purple-600 text-sm font-normal font-['Open Sans'] leading-tight"
            >
                Очистить всё
            </button>
        </template>
        <template #file-upload>
            <div class="flex flex-col gap-y-8">
                <div
                    class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed"
                >
                    Медиафайлы
                </div>
                <div
                    class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight"
                >
                    Прикрепите файл
                </div>
                <div>
                    <label
                        class="cursor-pointer transition px-6 py-2 bg-blue-950 animation hover:bg-transparent rounded-full shadow-inner border border-white border-opacity-10 text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight custom-file-upload"
                    >
                        <input
                            type="file"
                            multiple
                            class="hidden"
                            accept="image/*,video/*"
                            @change="handleFileUpload($event)"
                        />
                        Загрузить файл
                    </label>
                </div>
            </div>
            <n-image-group>
                <draggable
                    v-model="images"
                    group="people"
                    item-key="id"
                    class="grid sm:justify-items-end justify-items-center content-center grid-cols-3 sm:grid-cols-2 xl:grid-cols-5 md:grid-cols-3 lg:grid-cols-4 sm:pt-0 pt-8 sm:justify-end gap-2"
                >
                    <transition-group name="delete">
                        <div
                            v-for="(image, index) in images"
                            :key="'img-' + index"
                            class="sm:h-[8.5rem] sm:w-[8.5rem] md:w-28 md:h-28 h-[26vw] w-[26vw] rounded-lg relative"
                        >
                            <template
                                v-if="
                                    image.file &&
                                    image.file.type.includes('image/')
                                "
                            >
                                <n-image
                                    :src="image.displayImage"
                                    alt=""
                                    class="absolute top-0 left-0 object-cover w-full h-full"
                                    style="border: 2px solid #4d5170; border-radius: 10px; padding: 3px;"
                                />
                            </template>
                            <template
                                v-else-if="
                                    image.file &&
                                    image.file.type.includes('video/')
                                "
                            >
                                <video
                                    :src="image.displayImage"
                                    autoplay
                                    loop
                                    muted
                                    class="absolute top-0 left-0 object-cover w-full h-full"
                                    style="border: 2px solid #4d5170; border-radius: 10px; padding: 3px;"
                                ></video>
                            </template>
                            <div
                                @click="deleteImage(index)"
                                class="cursor-pointer absolute bottom-0 right-0 rounded m-1 bg-gray-700 bg-opacity-80"
                            >
                                <BaseIcon
                                    class="p-1 text-white"
                                    :path="mdiDelete"
                                />
                            </div>
                        </div>
                    </transition-group>
                </draggable>
            </n-image-group>
        </template>
        <template #post-preview>
            <n-image-group v-if="images.length">
                <transition-group
                    name="fade"
                    tag="div"
                    :class="[
                        images.length <= 2 ? 'grid-cols-1' : '',
                        images.length === 3 ? 'grid-cols-1 sm:grid-cols-2' : '',
                        images.length >= 4 && images.length <= 6
                            ? 'grid-cols-2'
                            : '',
                        images.length > 6 ? 'grid-cols-3' : '',
                    ]"
                    class="grid gap-2"
                >
                    <div
                        v-for="(image, index) in images"
                        :key="'img-' + index"
                        class="bg-center bg-cover"
                    >
                        <template
                            v-if="
                                image.file && image.file.type.includes('image/')
                            "
                        >
                            <n-image
                                :src="image.displayImage"
                                alt=""
                                class="w-full h-full"
                            />
                        </template>
                        <template
                            v-else-if="
                                image.file && image.file.type.includes('video/')
                            "
                        >
                            <video
                                :src="image.displayImage"
                                autoplay
                                loop
                                muted
                            />
                        </template>
                    </div>
                </transition-group>
            </n-image-group>

            <div v-else>
                <img :src="uploadedImageUrl" class="object-cover" alt="" />
            </div>
            <div
                class="break-words pt-2 text-violet-100 text-base font-normal font-['Inter'] leading-tight"
                v-html="htmlContent"
            ></div>
        </template>
        <div class="mt-4 flex justify-center">
            <button
                @click.prevent="patchPattern"
                class="save__button text-violet-100 px-6 py-2 transition btn_gradient-purple hover:bg-purple-800 rounded-full text-lg"
            >
                Сохранить
            </button>
        </div>
    </TemplateLayout>
</template>

<style lang="scss">
.ql-toolbar {
    border-radius: 20px 20px 0 0;
    background: #eae0ff;
}
.ql-container {
    border-radius: 0 0 20px 20px;
    border: 1px solid #eae0ff;
    height: auto;
    transition: height 1s ease-out;
    @media screen and (max-width: 640px) {
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

.delete-enter-active,
.delete-leave-active {
    transition: all 0.5s;
}

.delete-enter,
.delete-leave-to {
    opacity: 1;
    transform: scale(1);
}

.delete-leave-active {
    animation: example 0.5s;
}

.ql-container.empty-editor {
    height: 227px;
    transition: height 1s ease-out;
}

.ql-editor.ql-blank::before {
    color: #eae0ff;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 130%; /* 20.8px */
}
.before {
    padding: 50px 40px;
    background: url("/images/background.jpg") center;
    background-size: cover;
    border-radius: 20px;
    @media screen and (max-width: 1024px) {
        padding: 10px;
    }
    .before_container {
        border-radius: 20px;
        background: #176073;
        padding: 20px;
    }
}
.blok {
    background: radial-gradient(
        278.82% 137.51% at 1.95% 3.59%,
        rgba(255, 255, 255, 0.4) 0%,
        rgba(81, 63, 255, 0) 100%
    );
}
.break-words {
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word; /* Non standard for webkit */
    white-space: pre-wrap; /* CSS3 */
}

.save__button {
    box-shadow: 0 0 4px #9d9d9d;
    &:hover {
        box-shadow: 0 0 10px #9d9d9d;
    }
}
</style>
