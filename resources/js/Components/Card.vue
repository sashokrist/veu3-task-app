<template>
  <div class="p-4 bg-white rounded shadow border mb-4">
    <h2 class="text-lg font-bold mb-2">{{ title }}</h2>
    <slot />
  </div>
</template>

<script setup>
defineProps({
  title: String
})
</script>
