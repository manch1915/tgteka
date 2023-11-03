<script setup>

</script>

<template>
    <div class="relative">
        <div class="alert">
            <div class="text-center">
                <h2 class="text-center text-violet-100 text-3xl font-bold font-['Open Sans'] leading-10 mb-12">Ваша
                    заявка принята</h2>
                <p class="text-center text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Мы ответим
                    на неё в течение 1 рабочего дня</p>
            </div>
        </div>
        <div class="absolute alert">

        </div>
    </div>
</template>

<style scoped lang="scss">
.alert {
    padding: 80px 140px;
    border-radius: 0 40px 40px 40px;
    border: 1.5px solid #FFF;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
    backdrop-filter: blur(21px);
}

.alert.absolute {
    width: 100%;
    height: 100%;
    top: 50%;
    left: 50%;
    z-index: -1;
    transform: translate(-50%, -50%) rotate(10.604deg);
    border-radius: 0 59.486px 59.486px 59.486px;
    border: 2.603px solid #8163C0;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.40) 0%, rgba(255, 255, 255, 0.00) 100%);
    backdrop-filter: blur(16px);
}
</style>
