<script setup>
import { NInput, useMessage } from "naive-ui";
import { closeModal } from "jenesius-vue-modal";
import { inputThemeOverrides } from "@/themeOverrides.js";
import { ref } from "vue";

const props = defineProps({
    order_id: Number,
});

const message = useMessage();

const reportMessage = ref("");

const sendReport = () => {
    axios
        .post(route("report-send"), {
            report_message: reportMessage.value,
            order_id: props.order_id,
        })
        .then((r) => {
            if (r.response.data.message) {
                message.error(c.response.data.message);
            }
        })
        .catch((c) => {
            if (c.response.data.message) {
                message.error(c.response.data.message);
            }
        });
    closeModal();
};
</script>

<template>
    <div class="container">
        <main>
            <div class="top">
                <div class="header mb-8">
                    <div
                        class="float-right cursor-pointer"
                        @click.prevent="closeModal()"
                    >
                        <img src="/images/Icon-close.svg" alt="" />
                    </div>
                    <div
                        class="flex flex-col items-start justify-center gap-y-6 sm:items-center"
                    >
                        <h1
                            class="sm:text-center text-start text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10"
                        >
                            Напишите пожалуйста ваш текст жалобы
                        </h1>
                    </div>
                </div>
                <div class="sm:w-3/4 mx-auto">
                    <div class="sm:w-1/2 mx-auto">
                        <n-input
                            :theme-overrides="inputThemeOverrides"
                            @keydown.enter.prevent="sendReport"
                            v-model:value="reportMessage"
                            placeholder="Текст жалобы"
                            type="textarea"
                        />
                        <button
                            @click.prevent="sendReport"
                            class="py-2 w-full mt-6 btn_gradient-purple transition hover:bg-purple-800 rounded-3xl text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                        >
                            Отправить
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped lang="scss">
main {
    width: 100%;
    border-radius: 30px 30px;
    border: 1px solid rgba(101, 34, 217, 1);
    box-shadow: 0 3px 40px 0 rgba(41, 37, 0, 0.1);
}
.top {
    padding: 65px;
    border-radius: 30px;
    background: rgba(7, 12, 41, 1);

    @media screen and (max-width: 640px) {
        padding: 40px;
        height: auto;
    }
    .overflowing {
        overflow-y: auto;
        height: calc(100% - 35px);
        @media screen and (max-width: 640px) {
            height: calc(100% - 75px);
        }
    }
    .grid {
        grid-template-columns: minmax(0, 8fr) minmax(0, 4fr);
    }
}
</style>
