<template>
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
          <slot name="cards">

          </slot>
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
</template>
<script>
import InterestChannelsCard from "@/Components/Home/InterestChannelsCard.vue";

export default {
  name: 'InterestChannelsBlock',
    components: {InterestChannelsCard}
}
</script>
<style scoped lang="scss">

.interestChannels {
  background: url("/images/interestChannelsBackground.svg") no-repeat center;
  background-position-y: 40%;
  background-size: cover;
  padding-top: 400px;

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
