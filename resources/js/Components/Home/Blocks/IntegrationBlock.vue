<template>
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
            <div class="float-right"><span
                class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-loose">x</span><span
                class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">2</span><span
                class="text-violet-100 text-2xl font-bold font-['Open Sans'] leading-loose"> сэкономить <br/>время и деньги</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'IntegrationBlock'
}
</script>
<style scoped lang="scss">

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

.icons {
}

</style>