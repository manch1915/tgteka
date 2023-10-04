<template>
  <div class="card">
    <div class="handbook__card">
        <div class="content flex flex-col justify-between gap-4">
            <div class="flex justify-end">
                <img src="/images/virtual-reality-vr.svg" alt="">
            </div>
            <div class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-relaxed">Преимущество 1</div>
            <div class="text-violet-100 text-sm font-normal font-['Open Sans'] leading-relaxed">Расскажите, что отличает ваши услуги от услуг в других компаниях</div>
        </div>
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
    padding: 35px;
    border-radius: 0 75px 75px 75px;
    border: 2px solid rgba(255, 255, 255, 0.2);
  background: linear-gradient(
          to right bottom,
          rgba(255,255,255, .4) -100%,
          rgba(81, 63, 255, 0) 100%
  );
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
    background: linear-gradient(
              to right bottom,
              rgba(255,255,255, 1) -200%,
              rgba(255, 255, 255, 0) 100%
    );
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
