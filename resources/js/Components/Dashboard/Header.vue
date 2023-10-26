<script setup>
import {Link} from '@inertiajs/vue3'
</script>

<template>
    <header>
        <div class="container mx-auto py-6">
            <div class="flex justify-between items-center">
                <div class="logo">
                    <div class="px-6 py-3.5 bg-zinc-300 rounded-2xl justify-start items-start gap-2.5 inline-flex">
                        <div class="text-slate-900 text-lg font-bold font-['Open Sans'] leading-normal">ЛОГО</div>
                    </div>
                </div>
                <nav class="flex gap-x-3">
                    <div class="balance-elements px-6 py-1">
                        <div class="balance flex items-center gap-x-3">
                            <div class="border-r-[1px] pr-2">
                                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Общий баланс 0 ₽</p>
                            </div>
                            <div class="border-r-[1px] pr-2 flex items-center gap-x-1">
                                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">Пополнить</p>
                                <div><img src="/images/group.svg" alt=""></div>
                            </div>
                            <div class="flex items-center gap-x-1">
                                <p class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal">0 ₽</p>
                            </div>
                        </div>
                    </div>
                    <div class="interactive flex items-center gap-x-3">
                        <div class="border-r-[1px] px-5">
                            <img src="/images/messenger.svg" alt="">
                        </div>
                        <div class="border-r-[1px] px-5">
                            <img src="/images/notification.svg" alt="">
                        </div>
                        <div class="border-r-[1px] px-5">
                          <Link :href="route('personal-data')">
                            <img src="/images/person.svg" alt="">
                          </Link>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>
    <div class="nav">
        <div class="container mx-auto">
        <div class="py-10">
            <ul class="flex gap-x-5 justify-center">
                <Link :href="route('patterns')"><li class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal cursor-pointer">Мои шаблоны</li></Link>
                <li class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal cursor-pointer">Мои размещения</li>
                <Link :href="route('channels-catalog')"><li class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal cursor-pointer">Каталог каналов</li></Link>
                <li class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal cursor-pointer">Мои каналы</li>
                <li class="text-violet-100 text-lg font-bold font-['Open Sans'] leading-normal cursor-pointer">Заявки на размещение</li>
            </ul>
        </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
header {
    background: #1C1D6D;

    .balance-elements {
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.10);
        background: #171961;
        box-shadow: 0 4px 4px 0 rgba(0, 0, 0, 0.25) inset;
    }
}
.nav{
    background: #0D143A;
    li:hover{
        filter: drop-shadow(0 0 5px rgb(237 233 254));
    }
}
.interactive{
  div{
    img{
      cursor: pointer;
      transition: all .5s;
      &:hover{
        filter: drop-shadow(0 0 5px #8729FF);
        -webkit-filter: drop-shadow(0 0 5px #8729FF);
      }
    }
  }
}
</style>
