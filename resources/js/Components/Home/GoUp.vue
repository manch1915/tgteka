<template>
    <div class="fixed right-[5%] bottom-[5%] z-50">
        <transition name="fade">
        <div
            @click.prevent="toUp"
            v-show="showButton"
            class="up cursor-pointer sm:flex hidden flex-col items-center gap-1"
        >
            <img
                class="transition w-14"
                src="/images/arrow-circle-down.svg"
                alt="arrow"
            />
        </div>
        </transition>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const showButton = ref(false);

const toUp = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const handleScroll = () => {
    showButton.value = window.scrollY > 1000;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>
<style scoped lang="scss">
$bg: linear-gradient(
        167deg,
        rgba(109, 66, 199, 1) 10%,
        rgba(66, 38, 72, 1) 100%
);
$mask: "/images/background-Vector-1.svg";
$maskHeight: 22vw; /* адаптив */
$maskPos: "top";
$minHeight: 300px;
$offset: 0px;

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

footer {
  //overflow: auto;
}

.up {
  &:hover {
    img {
      -webkit-filter: drop-shadow(3px 3px 2px rgba(255, 255, 255, 1));
      filter: drop-shadow(0 0 2px rgba(255, 255, 255, 1));
    }
  }
}
</style>
