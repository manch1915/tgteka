<script setup>
const props = defineProps({
    header: String,
    paragraph: String
})
</script>
<template>
  <main class="main main-padding">
      <div class="icon-container">
          <img src="/images/bgcomputer.svg" alt="Icon 1" class="icon icon1"/>
          <img src="/images/bgmessange.svg" alt="Icon 1" class="icon icon2"/>
          <img src="/images/bgnotification.svg" alt="Icon 1" class="icon icon3"/>
          <img src="/images/bgplane.svg" alt="Icon 1" class="icon icon4"/>
          <img src="/images/bgtelegram.svg" alt="Icon 1" class="icon icon5"/>
          <img src="/images/bgwindow.svg" alt="Icon 1" class="icon icon6"/>
      </div>
    <div class="container mx-auto main__block  max-w-4xl">
      <div class="flex flex-col items-center justify-center gap-10">
        <div>
          <div class="text-5xl font-bold text-paleblue text-center">{{props.header}}</div>
        </div>
        <p class="text-lg text-paleblue text-center">{{props.paragraph}}</p>
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
    .main__block{
        z-index: 9;
    }
    & .icon-container {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        overflow: hidden;
        img{
            filter: drop-shadow(0 0 5px #ffff);
        }
    }

    & .icon {
        position: absolute;
        /* Common icon styles (width, height, etc.) go here */
        max-width: 100px; /* Or whatever size you want */
        max-height: 100px; /* Or whatever size you want */
        width: 100%; /* Will resize down to the max-width */
        height: auto; /* Will resize down to the max-height */

    }

    & .icon1 {
        width: 88px;
        height: 88px;
        top: 70%;
        left: 70%;
    }
    & .icon2 {
        width: 40px;
        height: 40px;
        top: 57%;
        left: 32%;
    }
    & .icon3 {
        width: 47px;
        height: 47px;
        top: 40%;
        left: 80%;
    }
    & .icon4 {
        max-width: 420px; /* Or whatever size you want */
        max-height: 195px;
        top: 22%;
        left: 60%;
    }
    & .icon5 {
        width: 85px;
        height: 85px;
        top: 35%;
        left: 20%;
    }
    & .icon6 {
        width: 85px;
        height: 85px;
        top: 70%;
        left: 20%;
    }
  &::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('/images/background.svg') no-repeat bottom center;
    filter: drop-shadow(0 0 15px #D67EE2);
    z-index: 0;
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
