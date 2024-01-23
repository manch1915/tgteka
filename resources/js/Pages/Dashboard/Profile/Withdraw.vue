<script setup>
import ProfileLayout from "@/Layouts/ProfileLayout.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import {NCheckbox, NInput, NTabPane, NTabs} from "naive-ui";
import {checkboxThemeOverrides, inputThemeOverrides, nTabSegmentsThemeOverrides} from "@/themeOverrides.js";
import { reactive, ref } from "vue";
const activeButton = ref('self-employed');

const activeTab = ref('bank-card')

const read = ref(false)

const bankCard = reactive({
    cardNumbers: null,
    amount: null,
})

const createPaymentRequest = () => {
    axios.post(route('create-payout-request'), bankCard)
        .then(r => {
            console.log(r);
        });
}

</script>

<template>
    <AppLayout>
        <ProfileLayout>
            <div class="text-center sm:text-left">
                <p class="text-violet-100 sm:text-4xl text-3xl font-bold font-['Open Sans'] leading-10">Вывод средств</p>
            </div>
            <div class="mt-8 segment sm:w-3/4">
                <n-tabs :animated="true" :theme-overrides="nTabSegmentsThemeOverrides" type="segment">
                    <n-tab-pane name="self-employed">
                        <template #tab>
                            <button @click.prevent="activeButton = 'self-employed'" :class="['tab-button', 'transition', 'text-violet-100', 'text-lg', 'font-bold', 'font-[\'Open Sans\']', 'leading-normal', activeButton === 'self-employed' ? 'active' : '']">Самозанятые</button>
                        </template>
                        <div class="px-4 sm:px-0">
                            <div class="py-2">
                                <h2 class=" text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Обратите внимание, что сменилось приложение для автоматического формирования чеков.</h2>
                                <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">При оформлении новой заявки на вывод средств вам будет предложена пошаговая инструкция для подключения приложенияПри возникновении затруднений рекомендуем ознакомиться с подробным гайдом по ссылке:Как вывести деньги, если вы «Самозанятый»</p>
                            </div>
                            <div class="py-2">
                                <h2 class=" text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Как стать самозанятым?</h2>
                                <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Открытие, как и закрытие статуса, занимает5 минути делается онлайн через приложение «Мой налог» наiOSиAndroid.Подробнее о том как получить статус самозанятого читайтев статье.</p>
                            </div>
                            <div class="py-2">
                                <h2 class=" text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Не могу быть самозанятым по личным причинам, что делать?</h2>
                                <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Если у Телеграм-канала несколько владельцев или менеджеров, cамозанятость может быть оформлена на любого из них. Такой способ ничем не отличается от вывода на карту одному физическому лицу.</p>
                            </div>
                            <div class="flex py-4 gap-x-2.5">
                                <img class="w-6" src="/images/calendar-bold.svg" alt="calendar">
                                <p class="text-purple-600 text-lg font-bold font-['Open Sans']">График выводов</p>
                            </div>
                            <div>
                                <h1 class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">Вывод на банковскую карту для самозанятых</h1>
                                <div class="mt-6 sm:w-3/4">
                                    <n-input class="py-1.5 my-1 sm:!w-3/4" placeholder="ИНН" :theme-overrides="inputThemeOverrides"/>
                                    <n-input class="py-1.5 my-1 sm:!w-3/4" placeholder="ФИО" :theme-overrides="inputThemeOverrides"/>
                                    <n-input class="py-1.5 my-1 sm:!w-3/4" placeholder="Сумма, рублей" :theme-overrides="inputThemeOverrides"/>
                                    <n-input class="py-1.5 my-1 sm:!w-3/4" placeholder="Действующий на основании" :theme-overrides="inputThemeOverrides"/>
                                </div>
                            </div>
                            <div class="my-4">
                                <div class="flex items-start gap-x-2.5">
                                    <img src="/images/information.svg" alt="information">
                                    <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Комиссия системы за вывод составляет 1%Минимальная сумма вывода составляет 1500 р.Максимальная сумма одной заявки на вывод составляет 100000 р.</p>
                                </div>
                                <div class="flex items-baseline py-2 gap-x-2.5">
                                    <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                                    <p class="sm:w-2/4 text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">Достоверность данных подтверждаю, с условиями Оферты ознакомлен и принимаю их. Сформировать и подписать акт приемки-передачи оказанных услуг за расчетный период</p>
                                </div>
                            </div>
                            <button class="block sm:w-2/4 w-full my-2 bg-purple-600 rounded-3xl py-2 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Вывести</button>
                        </div>
                    </n-tab-pane>
                    <n-tab-pane name="natural">
                        <template #tab>
                            <button @click.prevent="activeButton = 'natural'" :class="['tab-button', 'transition', 'text-violet-100', 'text-lg', 'font-bold', 'font-[\'Open Sans\']', 'leading-normal', activeButton === 'natural' ? 'active' : '']">Физическое лицо</button>
                        </template>
                        <div class="px-4 sm:px-0">
                            <div class="dashed">
                                <div class="flex items-start gap-x-2.5">
                                    <img src="/images/information.svg" alt="information">
                                    <div>
                                        <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">При выводе средств в качестве физического лица применяется динамический процент комиссии. Его размер варьируется в пределах 8-15% (не менее 85р.) в зависимости от общего объема вывода денежных средств в календарном месяце. Экономьте на выводе, переключайтесь на режим “Самозанятого” или ИП/ООО и выводите под 1% (не менее 85р.) и 0%!</p>
                                        <div v-show="read" class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">
                                            <div class="py-4">
                                            <p>1) до 30000 р. комиссия составляет 8%, (но не менее 85р.)</p>
                                            <p>2) От 30000 до 50000 р. — 10%.</p>
                                            <p>3) От 50000 до 100000 р. — 12%.</p>
                                            <p>4) От 100000 р. и выше — 15%.</p>
                                            </div>
                                            <p class="py-4">Пример 1: комиссия на суммарный вывод в размере 65000 р. в месяц составит: 30000*0.08 + 20000*0.10 + 15000*0.12 = 6200 р. (или 9.5% от общей суммы).Пример 2: комиссия на вывод 35000 р. в месяц составит: 30000*0.08 + 5000*0.10 = 2900 р. (или 8.3% от общей суммы).</p>
                                            <div class="rounded-2xl p-5" style="background: #2F334B;">
                                                <h2 class="text-violet-100 pb-6 text-lg font-bold font-['Open Sans'] leading-normal">Советы:</h2>
                                                <p class="text-violet-100 py-2 text-base font-normal font-['Open Sans'] leading-tight">1) Выводите крупные суммы? Переходите на вывод через Самозанятость или ИП/ООО с комиссией 1% (не менее 85р.) и 0% соответственно, экономьте на выводе и получайте выплаты быстрее!</p>
                                                <p class="text-violet-100 py-2 text-base font-normal font-['Open Sans'] leading-tight">2) Тратьте деньги внутри Телеги — на затраты внутри проекта не взымается дополнительная комиссия, а также вы экономите на комиссии пополнения!</p>
                                                <p class="text-violet-100 py-2 text-base font-normal font-['Open Sans'] leading-tight">3)База для расчета комиссии “обнуляется” в конце месяца, можно немного подождать и вывести дешевле. Но выводы на Самозанятых и ИП/ООО почти бесплатные и без ограничений!</p>
                                            </div>
                                        </div>
                                        <div @click.prevent="read = !read" class="flex cursor-pointer items-center gap-x-2.5">
                                            <h2 class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Читать подробнее</h2>
                                            <img src="/images/arrow-down.svg" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="payment_types">
                                <div class="grid grid-cols-2 gap-4 payment_types-cards sm:grid-cols-3">
                                    <div @click.prevent="activeTab = 'bank-card'" class="payment_types-card-wrapper">
                                        <div class="flex h-full flex-col items-center justify-center payment_types-card">
                                            <img src="/images/card-white.svg" alt="card-white">
                                            <p class="text-violet-100 text-lg font-normal font-['Open Sans']">Банковская карта</p>
                                        </div>
                                    </div>
                                    <div @click.prevent="activeTab = 'yookassa'" class="payment_types-card-wrapper">
                                        <div class="flex h-full flex-col items-center justify-evenly payment_types-card">
                                            <img src="/images/yookassa.png" alt="yookassa">
                                            <p class="text-violet-100 text-lg font-normal font-['Open Sans']">Yookassa</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-show="activeTab === 'bank-card'" class="mt-5 bank-card sm:w-3/4">
                                <div class="flex py-4 gap-x-2.5">
                                    <img class="w-6" src="/images/calendar-bold.svg" alt="calendar">
                                    <p class="text-purple-600 text-lg font-bold font-['Open Sans']">График выводов</p>
                                </div>
                                <h1 class="text-violet-100 sm:text-3xl sm:text-left text-center text-xl font-bold font-['Open Sans'] leading-10">Вывод на банковскую карту</h1>
                                <div class="py-8">
                                    <n-input class="py-1.5 my-1 sm:!w-3/4" v-model:value="bankCard.cardNumbers" placeholder="420XXXXXXXXХХ000" :theme-overrides="inputThemeOverrides"/>
                                    <n-input class="py-1.5 my-1 sm:!w-3/4" v-model:value="bankCard.amount" placeholder="Сумма, рублей" :theme-overrides="inputThemeOverrides"/>
                                </div>
                                <div class="my-4">
                                    <div class="flex items-center gap-x-2.5">
                                        <img src="/images/database-import.svg" alt="">
                                        <p class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">Выведено за текущий месяц: 0₽</p>
                                    </div>
                                    <div class="flex items-center gap-x-2.5">
                                        <img src="/images/information-white.svg" alt="">
                                        <p class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">Максимальная сумма одной заявки на вывод составляет 35000 р.</p>
                                    </div>
                                </div>
                                <button @click.prevent="createPaymentRequest" class="sm:w-3/4 w-full my-4 bg-purple-600 rounded-3xl py-2 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Вывести</button>
                                <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">При необходимости вы можете создать несколько заявок или выбрать альтернативный <br/>способ вывода средств.</p>
                            </div>
                            <div v-show="activeTab === 'yookassa'" class="mt-5 wallets sm:w-3/4">
                                <div class="flex py-4 gap-x-2.5">
                                    <img class="w-6" src="/images/calendar-bold.svg" alt="calendar">
                                    <p class="text-purple-600 text-lg font-bold font-['Open Sans']">График выводов</p>
                                </div>
                                <h1 class="text-violet-100 sm:text-3xl sm:text-left text-center text-xl font-bold font-['Open Sans'] leading-10">Вывод на Yookassa</h1>
                                <div class="py-8">
                                    <n-input class="py-1.5 my-1 sm:!w-3/4" placeholder="420XXXXXXXXХХ000" :theme-overrides="inputThemeOverrides"/>
                                    <n-input class="py-1.5 my-1 sm:!w-3/4" placeholder="Сумма, рублей" :theme-overrides="inputThemeOverrides"/>
                                </div>
                                <div class="my-4">
                                    <div class="flex items-center gap-x-2.5">
                                        <img src="/images/database-import.svg" alt="">
                                        <p class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">Выведено за текущий месяц: 0₽</p>
                                    </div>
                                    <div class="flex items-center gap-x-2.5">
                                        <img src="/images/information-white.svg" alt="">
                                        <p class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">Максимальная сумма одной заявки на вывод составляет 35000 р.</p>
                                    </div>
                                </div>
                                <button class="sm:w-3/4 w-full my-4 bg-purple-600 rounded-3xl py-2 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Вывести</button>
                                <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">При необходимости вы можете создать несколько заявок или выбрать альтернативный <br/>способ вывода средств.</p>
                            </div>
                        </div>
                    </n-tab-pane>
                    <n-tab-pane name="org">
                        <template #tab>
                            <button @click.prevent="activeButton = 'org'" :class="['tab-button', 'transition', 'text-violet-100', 'text-lg', 'font-bold', 'font-[\'Open Sans\']', 'leading-normal', activeButton === 'org' ? 'active' : '']">Юридическое лицо или ИП</button>
                        </template>
                        <div class="px-4 pb-12 org sm:px-0">
                            <div>
                                <div class="text-violet-100 text-xl font-bold font-['Open Sans'] leading-relaxed">Как вывести деньги на юридическое лицо или ИП?</div>
                                <div class="text-violet-100 py-2 text-base font-normal font-['Open Sans'] leading-tight">Оставьте заявку на вывод средств в данном разделе, заполнив все поля с реквизитами.</div>
                                <div class="text-violet-100 py-6 text-base font-normal font-['Open Sans'] leading-tight">В ближайший день вывода для вас будет подготовлен комплект документов необходимых в соответствии <br/>с процедурой оформления, предусмотренной на платформе (такими документами в зависимости от типа налогообложения и основания выплаты могут являться - Договор, Соглашение, Отчет, Акт, Счет). Передача документов происходит посредством нашего специального бота, в который вы предварительно получите приглашение.</div>
                                <div class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Выплата средств осуществляется после получения от вас комплекта подписанных документов, оригиналы которых необходимо отправить в срок не позднее 30 дней после каждой выплаты средств.</div>
                                <div class="flex py-4 gap-x-2.5">
                                    <img class="w-6" src="/images/calendar-bold.svg" alt="calendar">
                                    <p class="text-purple-600 text-lg font-bold font-['Open Sans']">График выводов</p>
                                </div>
                            </div>
                            <div class="mt-12">
                                <h1 class="pb-12">Создание заявки на вывод средств на расчетный счет</h1>
                                <div class="sm:w-2/4">
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Сумма, рублей"/>
                                </div>
                            </div>
                            <div class="mt-12 sm:w-2/4">
                                <h1 class="pb-12">Данные получателя:</h1>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Наименование ЮЛ"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Юридический адрес"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Физический адрес"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="ИНН"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="КПП"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Подписант (Должность и ФИО)"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Действующий на основании"/>
                            </div>
                            <div class="mt-12 sm:w-2/4">
                                <h1 class="pb-12">Реквизиты банковского счёта:</h1>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Расчётный счёт"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Банк"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Кор. счёт"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="БИК"/>
                            </div>
                            <div class="mt-12 sm:w-2/4">
                                <h1 class="pb-12">Контактная информация:</h1>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Контактный телефон"/>
                                <n-input class="my-1 py-1.5" :theme-overrides="inputThemeOverrides" placeholder="Электронная почта"/>
                                <div class="flex items-center gap-x-2.5">
                                    <n-checkbox :theme-overrides="checkboxThemeOverrides"/>
                                    <p class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-normal">Применение НДС</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 sm:px-0">
                            <div class="flex items-center justify-between py-8">
                                <h1 class="text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10">Сумма к оплате:</h1>
                                <h1 class="text-violet-100 sm:text-3xl text-xl font-bold font-['Open Sans'] leading-10">0 руб.</h1>
                            </div>
                            <div class="attention">
                                <div class="flex items-start gap-x-2.5">
                                    <div class="rounded-full bg-white" style="width: 20px; height: 20px"></div>
                                    <p class="text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Комиссия системы за вывод составляет 0%  Минимальная сумма вывода составляет 10000 р.</p>
                                </div>
                            </div>
                            <button class="sm:w-2/4 w-full py-2 bg-purple-600 rounded-full text-white text-lg font-bold font-['Open Sans'] leading-normal">Вывести</button>
                        </div>
                    </n-tab-pane>
                </n-tabs>
            </div>
        </ProfileLayout>
    </AppLayout>
</template>

<style scoped lang="scss">
.tab-button{
    border-radius: 100px;
    border: 1px solid  #6522D9;
    background: transparent;
    padding: 10px 25px;
    width: 100%;
    &:hover{
        background: #301864;
    }
}
.tab-button.active{
    border-radius: 100px;
    border: 1px solid  #6522D9;
    background: #301864;
    padding: 10px 25px;
}
.org{
    width: 100%;
    border-bottom: 1px solid rgba(234, 224, 255, 0.40);
    .org_commission{
        padding: 24px;
        border-radius: 20px;
        border: 1px dashed  #EAE0FF;
    }
    h1{
        color: #EAE0FF;
        font-family: Open Sans;
        font-size: 30px;
        font-style: normal;
        font-weight: 700;
        line-height: 130%; /* 39px */
        @media screen and (max-width: 640px){
            color: var(--White, #EAE0FF);
            text-align: center;
            font-family: Open Sans;
            font-size: 22px;
            font-style: normal;
            font-weight: 700;
            line-height: 130%; /* 28.6px */
        }
    }
}
.attention{
    padding: 24px;
    border-radius: 20px;
    border: 1px dashed  #EAE0FF;
}

.payment_types {
    .payment_types-cards {
        padding: 10px;
        .payment_types-card-wrapper {
            padding: 2px;
            background: #2F334B;
            transition: all .2s;
            border-radius: 20px;
            cursor: pointer;
            &:hover{
                background: linear-gradient(to bottom, #2712A5, #181856);
                box-shadow: 0 0 10px rgba(255,255,255,0.4);
            }
            .payment_types-card {
                padding: 40px 0;
                border-radius: 20px;
                background: #2F334B;
            }
        }
    }
}
.dashed{
    padding: 24px;
    border-radius: 20px;
    border: 1px dashed #EAE0FF;
}
</style>
