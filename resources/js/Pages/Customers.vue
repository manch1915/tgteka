<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import BookCard from "@/Components/Home/BookCard.vue";
import PointCard from "@/Components/Home/PointCard.vue";
import InterestChannelsCard from "@/Components/Home/InterestChannelsCard.vue";
import ChannelCard from "@/Components/Home/ChannelCard.vue";
import ClientsSlider from "@/Components/Home/ClientsSlider.vue";
</script>

<template>
    <MainLayout>
        <main class="main">
            <div class="container mx-auto">
                <div class="flex flex-col items-center justify-center gap-10">
                    <div>
                        <div class="text-5xl font-bold text-paleblue text-center">Нативные размещения в Телеграм:</div>
                        <div class="text-5xl font-bold text-paleblue text-center">проверенные вручную <br> каналы и боты
                        </div>
                    </div>
                    <p class="text-lg text-paleblue text-center">Нативные размещения в Телеграм: <br> проверенные
                        вручную
                        каналы и боты</p>
                    <button class="main_button text-lg font-bold text-paleblue text-center">Купить размещение</button>
                </div>
            </div>
        </main>
        <div class="mission">
            <div class="container mx-auto">
                <div class="py-6 flex flex-col items-center justify-center">
                    <p class="text-bold text-paleblue text-center py-6">Наша миссия</p>
                    <p class="text-bold text-paleblue text-center text-2xl">Объединить рекламодателей и владельцев
                        Telegram-каналов на выгодных <br>для обеих сторон условиях. Мы даём им простой инструмент для
                        взаимовыгодного
                        <br>сотрудничества и открываем новые возможности для роста.</p>
                    <div class="cards flex gap-6 py-6">
                        <book-card/>
                        <book-card/>
                        <book-card/>
                    </div>
                </div>
            </div>
        </div>
        <div class="platform pt-36">
            <div class="container mx-auto">
                <h1 class="text-4xl text-paleblue text-center font-bold">Как работает платформа?</h1>
                <div class="points grid grid-cols-3 gap-y-36 pt-24">
                    <point-card number=1 header="Регистрируйте аккаунт"
                                paragraph="Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc "/>
                    <point-card number=2 header="Выберите подходящие каналы в каталоге"
                                paragraph="Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc "/>
                    <point-card number=3 header="Пополните баланс удобным способом:"
                                paragraph="Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc "/>
                    <point-card reversed number=4 header="Создайте размещение с картинкой и ссылкой"
                                paragraph="Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc "/>
                    <point-card reversed number=5 header="Владелец канала публикует пост и присылает ссылку на проверку"
                                paragraph="Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc "/>
                    <point-card reversed number=6 header="Вы можете скачать отчет по итогу размещения"
                                paragraph="Lorem ipsum dolor sit amet consectetur. Sed et imperdiet at ultrices in. Arcu quam potenti nunc "/>
                </div>
            </div>
        </div>
        <div class="interestChannels">
            <div class="container mx-auto ">
                <div class="interestChannels__header mb-36">
                    <h1 class="text-center text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">
                        Интересные каналы из нашего каталога
                    </h1>
                </div>
                <div class="interestChannels__categories">
                    <div class="flex justify-between">
                        <div class="w-96 h-36 flex flex-col gap-6">
                            <div class="w-96 text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">
                                Все каналы в нашем каталоге отсортированы по категориям, вот некоторые из них
                            </div>
                            <div>
                                <div class="interestChannels__categories-button">
                                    <div class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                                        Посмотреть остальные
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between gap-4">
                            <InterestChannelsCard/>
                            <InterestChannelsCard/>
                            <InterestChannelsCard/>
                            <InterestChannelsCard/>
                        </div>
                    </div>
                    <div class="channels grid grid-cols-3 justify-between gap-9">
                        <ChannelCard/>
                        <ChannelCard/>
                        <ChannelCard/>
                        <ChannelCard/>
                        <ChannelCard/>
                        <ChannelCard/>
                    </div>
                    <div class="text-center py-20">
                        <div class="gotocatalog">
                            <div class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Перейти в
                                полный каталог
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <img src="/images/integr-background.svg" alt="">
        </div>
        <div class="integration">
            <div class="container mx-auto">
                <div class="integration__header text-center my-32"><span
                    class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Как купить интеграцию в Телеграм с </span><span
                    class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">TGteka.ru</span></div>
                <div class="grid grid-cols-2 justify-center items-center justify-items-center">
                    <div
                        class="samo flex flex-col p-12 pb-36 origin-top-left rounded-tr-3xl rounded-bl-3xl rounded-br-3xl border border-blue-950 backdrop-blur-sm gap-6">
                        <div class="icons flex gap-4">
                            <div class="icons_el">
                                <div
                                    class="text-violet-100 text-sm text-base font-bold font-['Open Sans'] leading-tight">
                                    ручной процесс
                                </div>
                            </div>
                            <div class="icons_el">
                                <div
                                    class="text-violet-100 text-sm text-base font-bold font-['Open Sans'] leading-tight">
                                    нет гарантий
                                </div>
                            </div>
                        </div>
                        <div class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">Самостоятельное
                            <br/>размещение напрямую
                        </div>
                        <ul class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-loose gap-4">
                            <li>Найти каталог Телеграм-каналов через Google</li>
                            <li>Проверить каналы на живых подписчиков</li>
                            <li>Написать каждому админу, чтобы узнать</li>
                            <li>стоимость интеграции</li>
                            <li>Оплатить размещение каждому админу</li>
                            <li>Написать посты для интеграции</li>
                            <li>Отправить пост каждому админу</li>
                            <li>Контролировать выход поста в каждом канале</li>
                        </ul>
                    </div>
                    <div
                        class="samo flex flex-col p-12 pb-36 origin-top-left rounded-tr-3xl rounded-bl-3xl rounded-br-3xl border border-blue-950 backdrop-blur-sm gap-6">
                        <div class="icons flex gap-4">
                            <div class="icons_el">
                                <div class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">ручной
                                    процесс
                                </div>
                            </div>
                            <div class="icons_el">
                                <div class="text-violet-100 text-sm font-bold font-['Open Sans'] leading-tight">нет
                                    гарантий
                                </div>
                            </div>
                        </div>
                        <div class="text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10">Автоматическое
                            размещение с TGteka.ru
                        </div>
                        <ul class="text-violet-100 text-lg font-normal font-['Open Sans'] leading-loose gap-4">
                            <li>Зарегистрируйтесь на платформе</li>
                            <li>Выберите подходящие каналы из каталога в один клик или купите размещение под ключ</li>
                            <li>Создайте интеграцию</li>
                            <li>Запустите нативную интеграцию</li>
                            <li>Получите оповещение о каждом размещении</li>
                        </ul>
                        <div>
                            <button
                                class="px-6 py-2 bg-purple-600 rounded-full text-violet-100 text-base font-bold font-['Open Sans'] leading-normal">
                                Создать проект
                            </button>
                        </div>
                        <div>
                            <div class="float-right"><span class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-loose">x</span><span class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">2</span><span class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-loose"> сэкономить <br/>время и деньги</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clients">
            <div class="container mx-auto">
            <div class="clients__header">
                <div class="text-center text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Что говорят наши клиенты</div>
            </div>
            <clients-slider/>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped lang="scss">
.main {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 235px 0;
    flex-direction: column;
    height: 100%;
    z-index: 2;

    &::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('/images/background.svg') no-repeat bottom center;
        filter: drop-shadow(0 0 15px #D67EE2);
        z-index: 1;
    }
}

.samo {
    max-width: 70%;
    min-height: 100%;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
    backdrop-filter: blur(1.5px);

    ul {
        li {
            list-style: circle;
        }
    }
}

.integration__header {
    position: relative;

    &:before {
        content: '';
        position: absolute;
        left: 0;
        bottom: 50%;
        transform: translateY(70%);
        width: 100%;
        height: 500%;
        background: url("/images/planeInter.svg") no-repeat center;
    }
}

.cards {

}

.icons {
    .icons_el {
        padding: 8px 12px;
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.10);
        background: #171961;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
    }
}
.clients__header {
    position: relative;
    width: 100%;

    &:before {
        content: '';
        position: absolute;
        left: -10%;
        bottom: 50%;
        transform: translateY(50%);
        width: 100%;
        height: 500%;
        background: url("/images/plane.svg") no-repeat center;
    }
}
.main_button {
    padding: 16px 25px;
    border-radius: 100px;
    border: 1px solid rgba(255, 255, 255, 0.10);
    background: #171961;
    box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25) inset;
}
.clients{
    background: url("/images/interestChannelsBackground.svg") no-repeat center;
    background-position-y: 35%;
    background-size: cover;
    padding-top: 400px;
}
.interestChannels {
    background: url("/images/interestChannelsBackground.svg") no-repeat center;
    background-position-y: 40%;
    background-size: cover;
    padding-top: 400px;

    .interestChannels__categories-button {
        display: flex;
        padding: 16px 25px;
        justify-content: center;
        align-items: center;
        gap: 10px;
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.10);
        background: #171961;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
    }

    .interestChannels__header {
        position: relative;
        width: 100%;

        &:before {
            content: '';
            position: absolute;
            left: -20%;
            bottom: 50%;
            transform: translateY(50%);
            width: 100%;
            height: 500%;
            background: url("/images/plane.svg") no-repeat center;
        }
    }
}

.gotocatalog {
    display: inline-flex;
    padding: 16px 25px;
    justify-content: center;
    align-items: center;
    gap: 10px;
    border-radius: 100px;
    border: 1px solid rgba(255, 255, 255, 0.10);
    background: #171961;
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
}
</style>
