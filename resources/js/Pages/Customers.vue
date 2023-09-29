<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import BookCard from "@/Components/BookCard.vue";
</script>

<template>
  <MainLayout>
    <main class="main">
      <div class="flex flex-col items-center justify-center gap-10">
        <div>
          <div class="text-5xl font-bold text-paleblue text-center">Нативные размещения в Телеграм:</div>
          <div class="text-5xl font-bold text-paleblue text-center">проверенные вручную <br> каналы и боты</div>
        </div>
        <p class="text-lg text-paleblue text-center">Нативные размещения в Телеграм: <br> проверенные вручную каналы и боты</p>
        <button class="main_button text-lg font-bold text-paleblue text-center">Купить размещение</button>
      </div>
    </main>
    <div class="mission">
      <div class="py-6 flex flex-col items-center justify-center">
        <p class="text-bold text-paleblue text-center py-6">Наша миссия</p>
        <p class="text-bold text-paleblue text-center text-2xl">Объединить рекламодателей и владельцев Telegram-каналов на выгодных <br>для обеих сторон условиях. Мы даём им простой инструмент для взаимовыгодного
          <br>сотрудничества и открываем новые возможности для роста.</p>
        <div class="cards flex gap-6 py-6">
          <book-card/>
          <book-card/>
          <book-card/>
        </div>
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
}
.cards{

}
.main_button{
  padding: 16px 25px;
  border-radius: 100px;
  border: 1px solid rgba(255, 255, 255, 0.10);
  background: #171961;
  box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
}
</style>
