<template>
  <div class="interestChannels">
    <div class="container mx-auto ">
      <div class="interestChannels__header sm:mb-36 sm:mt-0 mb-12">
        <h1 class="text-center text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">
          Интересные каналы из нашего каталога
        </h1>
      </div>
      <div class="interestChannels__categories">
        <div class="sm:flex justify-between hidden">
          <div class="flex flex-col gap-6">
            <div class="text-violet-100 text-xl font-normal font-['Open Sans'] leading-relaxed">
              Все каналы в нашем каталоге отсортированы <br> по категориям, вот некоторые из них
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
        <div :class="windowWidth <= 640 ? 'channels w-full grid grid-cols-1 justify-between gap-9' : 'channels grid grid-cols-3 justify-between gap-9'">
          <slot name="cards">

          </slot>
        </div>
        <div class="text-center py-20 sm:block hidden">
            <div class="gotocatalog transition-all">
                <button class="transition-all text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">
                    Перейти в полный каталог
                </button>
            </div>

        </div>
          <div class="block justify-between sm:hidden">
              <div class="flex flex-col gap-6">
                  <div class="text-center text-violet-100 text-lg font-normal font-['Open Sans'] leading-tight">
                      Все каналы в нашем каталоге отсортированы по категориям, вот<br> некоторые из них
                  </div>
              </div>
              <div class="flex justify-between gap-4">
                  <slider :interactive="false" slides="4" :freemode="true">
                      <template v-slot:slider>
                          <swiper-slide v-for="n in 4" :key="n">
                              <div class="flex justify-center py-14">
                                  <InterestChannelsCard/>
                              </div>
                          </swiper-slide>
                      </template>
                  </slider>
              </div>
              <div class="py-8">
                  <button class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal interestChannels__categories-button">
                          Посмотреть остальные
                  </button>
              </div>
          </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import InterestChannelsCard from "@/Components/Home/InterestChannelsCard.vue";
import {ref} from "vue";
import Slider from "@/Components/Home/Slider.vue";
import {SwiperSlide} from "swiper/vue";
const windowWidth = ref(window.innerWidth)
</script>
<style scoped lang="scss">

.interestChannels {
    background: url("/images/interestChannelsBackground.svg") no-repeat center;
    background-position-y: 40%;
    background-size: cover;
    padding-top: 400px;
    @media screen and (max-width: 640px) {
        background-position-y: -120px;

    }
    .interestChannels__categories-button {
        display: block;
        padding: 10px 20px;
        margin: 0 auto;
        justify-items: center;
        justify-content: center;
        align-items: center;
        gap: 10px;
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.10);
        background: #171961;
        box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25) inset;

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

            @media screen and (max-width: 640px) {
                display: none;
            }
        }
    }
}

.gotocatalog {
    border-radius: 100px;
    display: inline-block;
    button{
        padding: 16px 25px;
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.10);
        background: #171961;
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
    }
    &:hover {
        button{
            box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0.25) inset;
        }
        box-shadow: 0px 0px 17px 0px #7375AE;
    }
}


</style>
