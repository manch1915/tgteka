<script setup>


</script>
<template>
  <div class="mission">
    <div class="container mx-auto">
      <div class="py-6 flex flex-col items-center justify-center">
        <p class="text-bold text-paleblue text-center py-6">Наша миссия</p>
        <p class="text-bold text-paleblue text-center text-2xl">Объединить рекламодателей и владельцев
          Telegram-каналов на выгодных <br>для обеих сторон условиях. Мы даём им простой инструмент для
          взаимовыгодного
          <br>сотрудничества и открываем новые возможности для роста.</p>
        <div class="cards flex gap-6 py-6">
            <slot name="cards">

            </slot>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">

.cards {

}

</style>
