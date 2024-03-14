<script setup>

</script>

<template>

  <div class="integration">
    <div class="container mx-auto">
      <div class="integration__header text-center my-32"><span
          class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">Как купить интеграцию в Телеграм с </span><span
          class="text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">TGteka.ru</span></div>
      <div class="grid lg:grid-cols-2 grid-cols-1 lg:gap-0 gap-y-8 justify-center items-center justify-items-center">
            <slot name="comparePairs">

            </slot>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">

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
    background-size: 76vw;
  }
}

.icons {
}

</style>
