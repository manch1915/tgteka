<template>
  <div class="platform pt-36">
    <div class="container mx-auto">
      <h1 class="text-4xl text-paleblue text-center font-bold">Как работает платформа?</h1>
      <div class="points grid lg:grid-cols-3 grid-cols-1 gap-y-36 pt-24">
        <slot name="points"/>
      </div>
    </div>
  </div>
</template>
