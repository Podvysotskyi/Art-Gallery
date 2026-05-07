<script setup lang="ts">
import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from "@headlessui/vue";
import {XMarkIcon, ArrowLeftIcon, ArrowRightIcon} from "@heroicons/vue/24/outline";

const props = defineProps<{images: ImageDto[]}>()

const preview = ref<boolean>(false)
const index = ref<number>(0)

const image = computed<ImageDto | null>(() => props.images[index.value] ?? null)

function open(id: string) {
  index.value = props.images.findIndex(image => image.id === id)

  if (index.value >= 0) {
    preview.value = true
  }
}

function next() {
  if (index.value + 1 >= props.images.length) {
    index.value = 0
  } else {
    index.value++
  }
}

function previous() {
  if (index.value - 1 < 0) {
    index.value = props.images.length - 1
  } else {
    index.value--
  }
}

defineExpose({open})
</script>

<template>
  <TransitionRoot v-if="images.length > 0" as="template" :show="preview">
    <Dialog class="relative z-10" @close="() => preview = false">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to=""
                       leave="ease-in duration-200" leave-from="" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity"></div>
      </TransitionChild>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full justify-center p-4 text-center items-center">
          <TransitionChild as="template" enter="ease-out duration-300"
                           enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                           enter-to=" translate-y-0 sm:scale-100" leave="ease-in duration-200"
                           leave-from=" translate-y-0 sm:scale-100"
                           leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all pt-2 pb-4 md:w-4xl w-full">
              <div class="absolute top-0 right-0 pt-2 pr-2">
                <div class="rounded-md bg-white text-gray-400 hover:text-gray-500 cursor-pointer" @click="() => preview = false">
                  <span class="sr-only">Close</span>
                  <XMarkIcon class="size-6" aria-hidden="true"/>
                </div>
              </div>

              <div class="flex flex-col md:h-[80dvh] h-screen">
                <div class="flex justify-center">
                  <span class="font-bold">
                    {{ image?.title ?? '' }}
                  </span>
                </div>

                <div class="flex grow px-2 py-2">
                  <div v-if="images.length > 1" class="flex cursor-pointer items-center justify-center text-gray-400 hover:text-gray-500" @click="previous()">
                    <span class="sr-only">Previous</span>
                    <ArrowLeftIcon class="size-6" aria-hidden="true" />
                  </div>
                  <div v-if="image !== null" class="grow bg-contain bg-center bg-no-repeat mx-1 relative" :style="`background-image: url(${image!.path_placeholder})`">
                    <img class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 object-contain max-h-full max-w-full" :src="image!.path_original" :alt="`${image!.id} image`" loading="lazy" />
                  </div>
                  <div v-if="images.length > 1" class="flex cursor-pointer items-center justify-center text-gray-400 hover:text-gray-500" @click="next()">
                    <span class="sr-only">Next</span>
                    <ArrowRightIcon class="size-6" aria-hidden="true" />
                  </div>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>