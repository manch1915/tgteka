<script setup>
import { NInput, useMessage } from "naive-ui";
import { closeModal } from "jenesius-vue-modal";
import { inputThemeOverrides } from "@/themeOverrides.js";
import { ref } from "vue";
import { useOrdersStore } from "@/stores/OrdersStore.js";

const props = defineProps({
    orderId: Number,
});

const message = useMessage();

const post_link = ref("");
const ordersStore = useOrdersStore();

const toCheck = () => {
    axios
        .post(route("to-check-telegram-post"), {
            post_link: post_link.value,
            orderId: props.orderId,
        })
        .then((r) => console.log(r))
        .catch((c) => {
            if (c.response.data.message) {
                message.error(c.response.data.message);
            }
        });
    ordersStore.getOrders();
    closeModal();
};
</script>

<template>
    <div class="container">
        <main>
            <div class="top">
                <div class="header">
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
                            class="sm:text-center text-start text-violet-100 sm:text-3xl text-lg font-bold font-['Open Sans'] leading-10"
                        >
                            Сделайте заказ на проверку
                        </h1>
                    </div>
                </div>
                <div class="sm:w-3/4 w-full mx-auto">
                    <div
                        class="text-center my-8 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                    >
                        Вы разместили пост в соответствии со всеми требованиями,
                        указанными в заявке. Вы обязуетесь не удалять пост
                        <br />пока не истечёт его формат размещения.
                    </div>
                    <div
                        class="text-center my-8 text-violet-100 text-base font-normal font-['Open Sans'] leading-tight"
                    >
                        Отправьте ссылку на размещённый пост для проверки
                        заказчиком.
                    </div>
                    <div class="sm:w-1/2 w-full mx-auto">
                        <n-input
                            :theme-overrides="inputThemeOverrides"
                            v-model:value="post_link"
                            placeholder="Ссылка на пост (https://t.me/c/1494800310/727)"
                        />
                        <button
                            @click.prevent="toCheck"
                            class="py-2 w-full my-2 btn_gradient-purple rounded-3xl text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal"
                        >
                            Подтвердить
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
        padding: 10px;
        height: 70vh;
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
