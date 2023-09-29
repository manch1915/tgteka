<script setup>

</script>

<template>
  <div class="header">
    <div class="container mx-auto flex justify-between items-center p-6 nav-menu">
      <div class="logo">
        <img src="/images/logo.svg" alt="Application logo">
      </div>
      <nav class="navigation">
        <ul class="flex gap-6">
          <li class="text-paleblue font-bold">Заказчикам</li>
          <li class="text-paleblue font-bold">Владельцу канала</li>
          <li class="text-paleblue font-bold">Сервисы</li>
        </ul>
      </nav>

      <ul class="flex items-center gap-1 text-paleblue font-bold">
        <li><img src="/images/house-user.svg" alt="home"></li>
        <li>Регистрация / Войти</li>
      </ul>
    </div>
  </div>
</template>


<style lang="scss" scoped>
    .header{
      border-bottom: 1px solid rgba(234, 224, 255, 0.3);
    }
</style>
