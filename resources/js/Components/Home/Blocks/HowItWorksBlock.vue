<template>
  <div class="sm:pt-36 pt-12 platform">
    <div class="container mx-auto">
      <h1 class="text-center text-4xl font-bold text-paleblue">Как работает платформа?</h1>
      <div class="grid grid-cols-1 sm:gap-y-36 gap-y-12 pt-24 points lg:grid-cols-3">
        <slot name="points"/>
      </div>
    </div>
  </div>
</template>
