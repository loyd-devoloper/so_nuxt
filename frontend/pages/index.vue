<template>
    <div>
        example
          <UButton color="primary"> <nuxt-link to="/about">about</nuxt-link></UButton>

         <UAlert
    title="Heads up!"
    description="You can change the primary color in your app config."
    icon="i-lucide-terminal"
  />
     <USelectMenu v-model="value" multiple :items="items" class="w-48" />


    </div>
</template>
<script setup lang="ts">
const items = ref(['Backlog', 'Todo', 'In Progress', 'Done'])
const value = ref(['Backlog', 'Todo'])
</script>