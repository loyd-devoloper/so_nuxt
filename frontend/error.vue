<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex flex-col items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
      <!-- Error Header -->
      <div class="bg-red-500 p-6 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <h1 class="text-2xl font-bold text-white mt-4">Oops! Something went wrong</h1>
      </div>

      <!-- Error Content -->
      <div class="p-8 text-center">
        <div class="mb-6">
          <span class="inline-block bg-red-100 text-red-800 text-5xl font-bold px-4 py-2 rounded-lg">
            {{ error?.statusCode || 'Error' }}
          </span>
        </div>
        
         <p class="text-gray-600 mb-2 text-lg">
          <template v-if="error?.statusCode === 429">
            {{ error?.statusMessage || 'An unexpected error occurred' }}
            <br>
            <p class="text-sm">            Please wait 1 minute before trying again
</p>
            </template>
          <template v-else>
            {{ error?.statusMessage || 'An unexpected error occurred' }}
          </template>
        </p>
    

        <button 
          @click="handleError"
          class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
          </svg>
 {{ returnButtonText }}        </button>
      </div>

     
    </div>
  </div>
</template>

<script lang="ts" setup>
import type { NuxtError } from '#app'
const route = useRoute()
const router = useRouter()
const props = defineProps({
  error: Object as () => NuxtError
})
const returnButtonText = computed(() => {
  return canGoBack() ? 'Go Back' : 'Return Home'
})

const canGoBack = () => {
  return window.history.length > 1
}

const handleError = async () => {
  await clearError()
  // if (canGoBack()) {
  //   console.log(router)
  //   router.back()
  // } else {
  //   router.push('/')
  // }
}
</script>

