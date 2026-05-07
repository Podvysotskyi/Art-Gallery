<script setup lang="ts">
const props = defineProps<{image: ImageDto}>()

const emit = defineEmits(['preview'])

const hasTitle = computed(() => props.image.title !== null)
</script>

<template>
  <div class="m-2 block cursor-pointer rounded-md overflow-hidden hover:shadow-lg/30 relative bg-gray-200 bg-cover bg-center" :style="`background-image: url(${props.image.path_placeholder})`" @click="emit('preview')">
    <img class="w-full aspect-square object-cover" :alt="props.image.title ?? `${image.id} image`" :src="image.path" loading="lazy" />
    <div class="absolute bottom-0 left-0 w-full h-full items-center justify-center bg-black/40 flex opacity-0 hover:opacity-100" v-if="hasTitle">
      <span class="font-bold text-white uppercase">
        {{ props.image.title }}
      </span>
    </div>
  </div>
</template>
