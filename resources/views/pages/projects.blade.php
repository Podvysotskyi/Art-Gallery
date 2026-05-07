<x-layouts::app :title="__('Welcome')">
    Projects
</x-layouts::app>

<script setup lang="ts">
import ProjectComponent from "@/components/ProjectComponent.vue";

const projects = await (async (): Promise<ProjectDto[]> => {
  const {data} = await useFetch<ProjectDto[]>('/api/projects');
  return data.value ?? []
})();
</script>

<template>
  <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
    <div class="grid grid-flow-row lg:grid-cols-3 md:grid-cols-2 grid-cols-1">
      <ProjectComponent v-for="project in projects" :key="project.id" :project="project" />
    </div>
  </div>
</template>
