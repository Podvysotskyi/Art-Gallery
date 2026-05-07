<script setup lang="ts">
import ImageComponent from '@/components/ImageComponent.vue'

const props = defineProps<{ story: StoryDto }>()

const imagePreviewRef = useTemplateRef('imagePreview')

const hasSubtitle = computed(() => props.story.subtitle !== null && props.story.subtitle.length > 0)
const hasDescription = computed(() => props.story.description !== null && props.story.description.length > 0)
const hasImages = computed(() => props.story.images.length > 0)
</script>

<template>
  <div class="flex justify-center mt-4">
    <span class="text-2xl font-bold">
      {{ props.story.title }}
    </span>
  </div>
  <div class="flex justify-center mt-2" v-if="hasSubtitle">
    <span class="text-sm text-gray-600">
      {{ props.story.subtitle }}
    </span>
  </div>
  <div class="flex justify-center my-4" v-if="hasDescription">
    <span class="text-sm font-thin">
      {{ props.story.description }}
    </span>
  </div>
  <div class="grid grid-flow-row xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 my-2" v-if="hasImages">
    <template v-for="(image, index) in props.story.images" :key="image.id">
      <ImageComponent :title="`${props.story.title} image ${index}`" :image="image"
                      @preview="imagePreviewRef!.open(image.id)"/>
    </template>
  </div>

  <ImagePreviewComponent :images="story.images" ref="imagePreview"/>
</template>