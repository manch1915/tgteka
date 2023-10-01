<script setup>

</script>

<template>
    <div class="main">
        <div class="interactive">

        </div>
        <div class="blocks grid grid-cols-2 gap-4">
            <div class="block">
                <div class="review text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва</div>
                <div class="pt-4 text-violet-100 text-opacity-60 text-sm font-normal font-['Poppins'] leading-tight">Анжелика Трофимова</div>
                <div class="text-violet-100 text-opacity-60 text-sm font-normal font-['Poppins'] leading-tight">26 мая 2021</div>
            </div>
            <div class="block">
                <div class="review text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва</div>
                <div class="pt-4 text-violet-100 text-opacity-60 text-sm font-normal font-['Poppins'] leading-tight">Анжелика Трофимова</div>
                <div class="text-violet-100 text-opacity-60 text-sm font-normal font-['Poppins'] leading-tight">26 мая 2021</div>
            </div>
            <div class="block">
                <div class="review text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва</div>
                <div class="pt-4 text-violet-100 text-opacity-60 text-sm font-normal font-['Poppins'] leading-tight">Анжелика Трофимова</div>
                <div class="text-violet-100 text-opacity-60 text-sm font-normal font-['Poppins'] leading-tight">26 мая 2021</div>
            </div>
            <div class="block">
                <div class="review text-violet-100 text-base font-normal font-['Open Sans'] leading-tight">Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва Текст отзыва</div>
                <div class="pt-4 text-violet-100 text-opacity-60 text-sm font-normal font-['Poppins'] leading-tight">Анжелика Трофимова</div>
                <div class="text-violet-100 text-opacity-60 text-sm font-normal font-['Poppins'] leading-tight">26 мая 2021</div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.block{
    padding: 50px 80px;
    border-radius: 1px 40px 40px 40px;
    border: 1.5px solid #FFF;
    background: radial-gradient(278.82% 137.51% at 1.95% 3.59%, rgba(255, 255, 255, 0.26) 0%, rgba(255, 255, 255, 0.00) 100%);
    backdrop-filter: blur(21px);

    .review{
        position: relative;
        &:before{
            content: '“';
            position: absolute;
            color: var(--White, #EAE0FF);
            font-family: Montserrat;
            font-size: 40px;
            font-style: normal;
            font-weight: 900;
            line-height: 140%; /* 56px */

            left: -30px;
            top: -20px;
        }
        &:after{
            content: '”';
            position: absolute;
            font-family: Montserrat;
            font-size: 40px;
            font-style: normal;
            font-weight: 900;
            line-height: 140%; /* 56px */
            right: -10px;
        }
    }
}
</style>
