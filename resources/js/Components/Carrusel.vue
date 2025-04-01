<template>
    <div class="relative w-full overflow-hidden rounded-xl">
      <!-- Slides -->
      <div
        class="flex transition-transform duration-500 ease-in-out"
        :style="{ transform: `translateX(-${currentIndex * 100}%)` }" 
      >
        <div v-for="(image, index) in images" :key="index" class="w-full flex-shrink-0">
          <img :src="image" alt="Slide" class="w-full h-64 object-cover">
        </div>
      </div>
  
      <!-- Prev/Next buttons -->
      <button
        @click="prev"
        class="absolute top-1/2 left-4 -translate-y-1/2 bg-white/80 p-2 rounded-full shadow hover:bg-white"
      >
        ‹
      </button>
      <button
        @click="next"
        class="absolute top-1/2 right-4 -translate-y-1/2 bg-white/80 p-2 rounded-full shadow hover:bg-white"
      >
        ›
      </button>
  
      <!-- Dots -->
      <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
        <span
          v-for="(image, index) in images"
          :key="index"
          @click="goTo(index)"
          :class="[
            'h-2 w-2 rounded-full cursor-pointer',
            currentIndex === index ? 'bg-white' : 'bg-white/50'
          ]"
        ></span>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const props = defineProps({
    images: {
      type: Array,
      required: true,
    },
  })
  
  const currentIndex = ref(0)
  
  function next() {
    currentIndex.value = (currentIndex.value + 1) % props.images.length
  }
  
  function prev() {
    currentIndex.value =
      (currentIndex.value - 1 + props.images.length) % props.images.length
  }
  
  function goTo(index) {
    currentIndex.value = index
  }
  </script>
  
  <style scoped>
  /* Optional: smooth transition */
  </style>
  