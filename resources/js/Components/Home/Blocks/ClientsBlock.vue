<script setup>

</script>
<template>
  <div class="clients">
    <div class="container mx-auto">
      <div class="clients__header">
        <div class="text-center text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">
            Что говорят наши клиенты
        </div>
      </div>
        <div class="pt-32">
      <slot name="slider"></slot>
        </div>
      <div class="review pt-12 flex justify-center">
        <div class="px-6 py-4 bg-purple-600 rounded-full justify-center items-center gap-2.5 inline-flex">
          <div class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Добавить отзыв</div>
        </div>
      </div>

      <div class="consultation flex flex-col items-center justify-center mt-40">
        <div
            class="text-center text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-violet-600 text-6xl font-bold font-['Open Sans'] ">
          Требуется консультация <br/>по работе сервиса?
        </div>
        <div class="consultation__block p-16 mt-14">
          <!--                            TODO gradient border-->
          <div class="form flex flex-col justify-center gap-y-2">
            <input type="text" placeholder="Имя">
            <input type="text" placeholder="+7(___) - ___ - __ - __">
            <div class="form__checkbox">
              <!--                            TODO checkbox styling-->
              <input type="checkbox" name="terms"
                     class="accent-gray-700 checked:accent-white bg-black text-gray-300 rounded cursor-pointer">
              <label for="terms"><span
                  class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-tight">Нажимая на кнопку «Отправить» я соглашаюсь <br/>с </span><span
                  class="text-violet-100 text-sm font-normal font-['Open Sans'] underline leading-tight">Правилами пользования Сервисом</span></label>
            </div>
            <button
                class="px-3 py-2 bg-purple-600 rounded-full w-full gap-2.5 text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
              Заказать обратный звонок
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">

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

.clients {
  background: url("/images/interestChannelsBackground.svg") no-repeat center;
  background-position-y: 50%;
  background-size: cover;
  padding-top: 400px;
}

.consultation {
    .consultation__block{
        border-radius: 40px 0 40px 40px;
        border: 1.5px solid #FFF;
        background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
        backdrop-filter: blur(21px);

        .form{
            input[type=text]{
                background: transparent;
                border-radius: 30px;
                border: 1px solid #FFF;
                color: #FFFFFF;
            }
        }

    }
}
</style>
