<script setup>

</script>
<template>
  <main class="main main-padding">
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
</template>

<style scoped lang="scss">
.main {
  display: flex;
  position: relative;
  justify-content: center;
  align-items: center;
  padding: 235px 0 0 0;
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

.main-padding {
  padding: 235px 0;
}

.main_button {
  padding: 16px 25px;
  border-radius: 100px;
  border: 1px solid rgba(255, 255, 255, 0.10);
  background: #171961;
  box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25) inset;
}

</style>
