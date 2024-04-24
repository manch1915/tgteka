<script setup>
import { reactive } from "vue";
import { vMaska } from "maska";
import { checkboxThemeOverrides } from "@/themeOverrides.js";
import { Link } from "@inertiajs/vue3";
import { NCheckbox, useLoadingBar, useMessage } from "naive-ui";

const loading = useLoadingBar();
const message = useMessage();

const form = reactive({
    name: "",
    email: "",
    terms: false,
    errors: {
        name: "",
        email: "",
        terms: "",
    },
});

const handleSuccess = () => {
    message.success("Ваша заявка на обратный звонок принято");
    form.name = "";
    form.email = "";
    form.terms = false;
};

const handleError = (e) => {
    loading.error();
    if (e.response) {
        if (e.response.data.errors) {
            const errors = e.response.data.errors;
            Object.keys(errors).forEach((key) => {
                form.errors[key] = errors[key][0];
            });
        } else if (e.response.data.message) {
            message.error("Произошла ошибка. Пожалуйста, попробуйте еще раз позже.");
        }
    }
};

const orderCallback = () => {
    loading.start();
    axios
        .post(route("order.callback"), form)
        .then(handleSuccess)
        .catch((e) => handleError(e))
        .finally(() => {
            loading.finish();
        });
};
</script>

<template>
    <div class="px-4">
        <div class="consultation__block sm:p-16 p-5 sm:mt-14 mt-10">
            <div
                class="form flex flex-col text-violet-100 justify-center gap-y-4"
            >
                <div>
                    <input
                        class="w-full"
                        v-model="form.name"
                        type="text"
                        placeholder="Имя"
                    />
                    <div class="error-message mt-1">{{ form.errors.name }}</div>
                </div>
                <div>
                    <input
                        class="w-full"
                        v-model="form.email"
                        type="email"
                        placeholder="example@gmail.com"
                    />
                    <div class="error-message mt-1">
                        {{ form.errors.email }}
                    </div>
                </div>
                <div>
                    <div class="form__checkbox flex items-center gap-x-2">
                        <n-checkbox
                            v-model:checked="form.terms"
                            :theme-overrides="checkboxThemeOverrides"
                        />
                        <label
                            class="terms text-violet-100 text-sm font-light font-['Open Sans'] leading-tight"
                        >
                            Нажимая на кнопку «Отправить» я соглашаюсь с
                            <Link :href="route('rules')" class="underline">
                                Правилами пользования Сервисом
                            </Link>
                        </label>
                    </div>
                    <div class="error-message mt-1">{{ form.errors.terms }}</div>
                </div>
                <button
                    @click.prevent="orderCallback"
                    class="cursor-pointer px-3 py-3.5 btn_gradient-purple animation hover:bg-purple-800 rounded-full w-full gap-2.5 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                >
                    Отправить
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.consultation__block {
    max-width: 570px;
    position: relative;
    border-radius: 0 40px 40px 40px;

    background: linear-gradient(
        49deg,
        rgba(255, 255, 255, 0.2) 0%,
        rgba(255, 255, 255, 0) 100%
    );
    backdrop-filter: blur(21px);
    // &:before {
    //     pointer-events: none;
    //     content: "";
    //     position: absolute;
    //     inset: 0;
    //     border-radius: inherit;
    //     padding: 1.5px;
    //     background: linear-gradient(
    //             360deg,
    //             rgba(24, 25, 94, 1) 0%,
    //             rgba(21, 21, 21, 0) 100%
    //         ),
    //         linear-gradient(
    //             360deg,
    //             rgba(78, 64, 97, 1) 0%,
    //             rgba(130, 120, 185, 1) 100%
    //         ),
    //         linear-gradient(
    //             360deg,
    //             rgba(255, 255, 255, 1) 0%,
    //             rgba(255, 255, 255, 0) 100%
    //         );
    //     -webkit-mask: linear-gradient(#fff 0 0) content-box,
    //         linear-gradient(#fff 0 0);
    //     -webkit-mask-composite: xor;
    //     mask-composite: exclude;
    // }

    &::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1.5px;
        background: rgb(24, 25, 94);
        pointer-events: none;
        background: linear-gradient(
                45deg,
                rgba(24, 25, 94, 1) 0%,
                rgba(21, 21, 21, 0) 100%
            ),
            linear-gradient(
                45deg,
                rgba(89, 61, 239, 1) 0%,
                rgba(89, 61, 239, 1) 100%
            ),
            linear-gradient(
                45deg,
                rgba(255, 255, 255, 1) 0%,
                rgba(255, 255, 255, 0) 100%
            );
        -webkit-mask: linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
    }

    .form {
        input[type="text"] {
            padding: 13px 14px;
            border-radius: 30px;
            background: transparent;
            border: 1px solid #ffffff;
        }
        input[type="email"] {
            padding: 13px 14px;
            border-radius: 30px;
            background: transparent;
            border: 1px solid #ffffff;
        }
    }
    &:after {
        content: "";
        position: absolute;
        display: block;
        right: -20%;
        bottom: -7%;
        background-image: url("/images/plane-consul.svg");
        background-size: contain;
        background-repeat: no-repeat;
        width: 405px;
        height: 120px;
        @media (max-width: 640px) {
            right: auto;
            height: 112px;
            left: 0;
            width: 100%;
            bottom: -50%;
            background-image: url("/images/plane-consul-2.svg");
        }
    }
}
.error-message {
    display: block;
    color: #ff8888;
    font-size: 0.8rem;
    line-height: 1em;
}
</style>
