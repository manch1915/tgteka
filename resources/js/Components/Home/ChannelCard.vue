<script setup>

</script>

<template>
    <div class="body p-6 pb-10 ">
        <div class="body__head flex justify-start gap-6 items-center">
            <div class="body__profile pl-3">
                <img src="https://via.placeholder.com/108x108" alt="" class="rounded-full">
            </div>
            <div class="body__data">
                <div>
                    <div class="text-center text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Доктор
                        Амина
                    </div>
                    <div class="text-center text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">127
                        995 подписчиков
                    </div>
                    <div
                        class="mt-4 sm:px-11 px-2 py-1.5 rounded-3xl border border-violet-100 justify-start items-start gap-2.5 inline-flex">
                        <div class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">1/24 — 12 500
                            руб.
                        </div>
                    </div>
                </div>
                <div></div>
            </div>
        </div>
        <div class="body__buttons grid grid-cols-2 gap-3 mt-3">
            <div class="body__button">
                <div class="text-center"><span
                    class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">CRM<br/></span><span
                    class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">905 руб.</span></div>
            </div>
            <div class="body__button">
                <div class="text-center"><span
                    class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Просмотров за 24 часа
<br/></span><span class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">49 002</span></div>
            </div>
            <div class="body__button">
                <div class="text-center"><span
                    class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">Статистика<br/></span>
                </div>
            </div>
        </div>
        <div class="order">
            <button class="order_el pl-6 pr-11 py-1.5 bg-purple-600 transition hover:bg-purple-800 rounded-tl-3xl rounded-br-3xl text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">
                Заказать размещение
            </button>
        </div>
    </div>
</template>

<style scoped lang="scss">
.body {
    position: relative;
    display: block;
    width: 100%;
    margin-top: 80px;
    border-radius: 40px 0 40px 40px;
    border: 1.5px solid #FFF;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
    backdrop-filter: blur(21px);
    box-sizing: border-box;
    overflow: hidden;
    @media screen and (max-width: 1024px) {
        margin-top: 20px;
    }
}

.body__buttons {
    .body__button {
        padding: 0px 12px;
        justify-content: center;
        align-items: center;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.10);
        box-shadow: 0px 1px 0px 0px rgba(255, 255, 255, 0.08) inset, 0px 4px 4px 0px rgba(0, 0, 0, 0.20);
    }
}
.order{
    position: absolute;
    bottom: 0;
    right: 0;
    .order_el{

    }
}
</style>
