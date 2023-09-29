<template>
  <div class="card">
    <div class="handbook__card">

    </div>
    <div class="handbook__card__second">

    </div>
  </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>
.card{
  position: relative;
  .handbook__card{
    div {
      transition: all 0.8s cubic-bezier(0.075, 0.82, 0.165, 1) 0s;
    }
    position: relative;
    width: 240px;
    height: 320px;
    padding: 20px;
    border-radius: 0px 74.358px 74.358px 74.358px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.40) 0%, rgba(81, 63, 255, 0.00) 100%);
    backdrop-filter: blur(15px);
    z-index: 1;
  }
  .handbook__card__second{

    transition: all 0.8s cubic-bezier(0.075, 0.82, 0.165, 1) 0s;
    position: absolute;
    width: 220px;
    height: 320px;
    border-radius: 0px 59.486px 59.486px 59.486px;
    border: 2.603px solid #8163C0;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.40) 0%, rgba(255, 255, 255, 0.00) 100%);
    backdrop-filter: blur(15px);
    transform: skewY(8deg);
    transform-origin: left top 0px;
    top: 0px;

    *, ::after, ::before {
      box-sizing: inherit;
    }
  }
  &:hover{
    .handbook__card__second {
      transform: skewY(15deg);
      width: 200px;
    }
  }
}
</style>
