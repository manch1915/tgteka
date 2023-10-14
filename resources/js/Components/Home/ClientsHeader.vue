<template>
  <div class="clients__header">
    <div class="text-center text-violet-100 text-4xl font-bold font-['Open Sans'] leading-10">
      Что говорят наши клиенты
    </div>
  </div>
</template>
<script>
</script>
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

</style>
