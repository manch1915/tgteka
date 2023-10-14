<script setup>
import { Link } from '@inertiajs/vue3';
import { useModalStore } from '@/stores/authModal.js'
import { openModal} from "jenesius-vue-modal";
import Login from "@/Components/Auth/Login.vue";
import Register from "@/Components/Auth/Register.vue";
import {watch} from "vue";

const modals = {
    register: Register,
    login: Login
};

const modalStore = useModalStore()
const closeModal = () => {
    modalStore.closeCurrentModal(); // Close the current modal
}
const openRegister = () => {
    closeModal();
    modalStore.setModalToOpen('register');
}

const openAuth = () => {
    closeModal();
    modalStore.setModalToOpen('login');
}

watch(() => modalStore.modalShouldOpen, (newModalName) => {
    if (newModalName) {
        closeModal() // Close any opened modal
        openModal(modals[newModalName]) // Open new modal
    }
})
</script>

<template>

  <div class="header">
    <div class="container mx-auto flex justify-between items-center p-6 nav-menu">
      <div class="logo">
        <img src="/images/logo.svg" alt="Application logo">
      </div>
      <nav class="navigation">
        <ul class="flex">
            <Link :href="route('customers')"><li class="text-paleblue font-bold py-1 px-4 cursor-pointer">Заказчикам</li></Link>
            <Link :href="route('owners')"><li class="text-paleblue font-bold py-1 px-4 cursor-pointer">Владельцу канала</li></Link>
          <li class="text-paleblue font-bold py-1 px-4 cursor-pointer flex gap-1">Сервисы <i class="arrow-circle-down"></i></li>
        </ul>
      </nav>

      <ul class="flex items-center gap-1 text-paleblue font-bold">
        <li><img src="/images/house-user.svg" alt="home"></li>
        <li>
            <span class="hover:text-violet-300 cursor-pointer" @click.prevent="openRegister">Регистрация</span>
             /
            <span class="hover:text-violet-300 cursor-pointer" @click.prevent="openAuth">Войти</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<style lang="scss" scoped>
    .header{
      border-bottom: 1px solid rgba(234, 224, 255, 0.3);
    }
    .arrow-circle-down{
        height: 24px;
        width: 24px;
        content: url("/images/arrow-circle-down.svg");
    }
    .navigation{
        ul{
            li{
                border-radius: 100px;
                border: 1px solid transparent;
                transition: all 0.5s;
                &:hover{
                    border-radius: 100px;
                    border: 1px solid rgba(255, 255, 255, 0.10);
                    background: #171961;
                    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25) inset;
                }
            }
        }
    }
</style>
