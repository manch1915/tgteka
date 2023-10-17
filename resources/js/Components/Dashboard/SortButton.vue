<script setup>
const props = defineProps({
  title: String,
})
</script>

<template>
  <button class="transition px-5 py-3 hover:bg-violet-950 rounded-full border border-violet-700 justify-start items-start text-violet-100 text-lg font-bold font-['Open Sans']">
    {{props.title}}
  </button>
</template>
