<script setup lang="ts">
defineProps<{project: ProjectDto}>();

function getImageUrl(path: string) : string {
  return `https://picsum.photos/${path}`;
}
</script>

<template>
<div class="block relative hover:shadow-sm/30 border rounded-sm border-gray-300 m-2 p-1">
  <div v-if="project.image !== null" class="block bg-gray-200 bg-cover bg-center" :style="`background-image: url(${getImageUrl(project.image.path_placeholder)})`">
    <img class="w-full aspect-square object-cover" :alt="project.image!.title !== null ? project.image.title : `${project.title} image`" :src="getImageUrl(project.image!.path)" loading="lazy" />
  </div>
  <div v-if="project.type !== null" class="block px-2 pt-2">
    <span class="text-sm text-gray-600 uppercase">
      {{ project.type }}
    </span>
  </div>
  <div class="block text-center px-1 py-2">
    <span class="text-2xl font-bold uppercase">
      {{ project.title }}
    </span>
  </div>
  <div class="block px-3 py-2 text-sm/6 text-gray-600" v-if="project.description !== null">
    <MDC :value="project.description" />
  </div>
</div>
</template>
