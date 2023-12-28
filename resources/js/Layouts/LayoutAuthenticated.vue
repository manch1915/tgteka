<script setup>
import { mdiForwardburger, mdiBackburger, mdiMenu } from "@mdi/js";
import {computed, ref} from "vue";
import menuAside from "@/menuAside.js";
import menuNavBar from "@/menuNavBar.js";
import { useMainStore } from "@/stores/main.js";
import { useStyleStore } from "@/stores/style.js";
import BaseIcon from "@/Components/Admin/BaseIcon.vue";
import FormControl from "@/Components/Admin/FormControl.vue";
import NavBar from "@/Components/Admin/NavBar.vue";
import NavBarItemPlain from "@/Components/Admin/NavBarItemPlain.vue";
import AsideMenu from "@/Components/Admin/AsideMenu.vue";
import {usePage} from "@inertiajs/vue3";
import { router } from '@inertiajs/vue3'
import {container} from "jenesius-vue-modal";

const page = usePage()
const user = computed(() => page.props.auth.user)
useMainStore().setUser({
  name: page.props.auth.user.name,
  email: page.props.auth.user.email,
  avatar:
    `https://api.dicebear.com/7.x/initials/svg?seed=${page.props.auth.user.name}`,
});

const layoutAsidePadding = "xl:pl-60";

const styleStore = useStyleStore();

router.on('navigate', () => {
    isAsideMobileExpanded.value = false
    isAsideLgActive.value = false
})

const isAsideMobileExpanded = ref(false);
const isAsideLgActive = ref(false);

const menuClick = (event, item) => {
  if (item.isToggleLightDark) {
    styleStore.setDarkMode();
  }

    if (item.isLogout) {
        // Add:
        router.post(route('logout'))
    }
};
</script>

<template>
  <div
    :class="{
      dark: styleStore.darkMode,
      'overflow-hidden lg:overflow-visible': isAsideMobileExpanded,
    }"
  >
    <div
      :class="[layoutAsidePadding, { 'ml-60 lg:ml-0': isAsideMobileExpanded }]"
      class="pt-14 min-h-screen w-screen transition-position lg:w-auto bg-gray-50 dark:bg-slate-800 dark:text-slate-100"
    >
      <NavBar
        :menu="menuNavBar"
        :class="[
          layoutAsidePadding,
          { 'ml-60 lg:ml-0': isAsideMobileExpanded },
        ]"
        @menu-click="menuClick"
      >
        <NavBarItemPlain
          display="flex lg:hidden"
          @click.prevent="isAsideMobileExpanded = !isAsideMobileExpanded"
        >
          <BaseIcon
            :path="isAsideMobileExpanded ? mdiBackburger : mdiForwardburger"
            size="24"
          />
        </NavBarItemPlain>
        <NavBarItemPlain
          display="hidden lg:flex xl:hidden"
          @click.prevent="isAsideLgActive = true"
        >
          <BaseIcon :path="mdiMenu" size="24" />
        </NavBarItemPlain>
        <NavBarItemPlain use-margin>
          <FormControl
            placeholder="Search (ctrl+k)"
            ctrl-k-focus
            transparent
            borderless
          />
        </NavBarItemPlain>
      </NavBar>
      <AsideMenu
        :is-aside-mobile-expanded="isAsideMobileExpanded"
        :is-aside-lg-active="isAsideLgActive"
        :menu="menuAside"
        @menu-click="menuClick"
        @aside-lg-close-click="isAsideLgActive = false"
      />
      <slot />
    </div>
  </div>
  <container/>
</template>
