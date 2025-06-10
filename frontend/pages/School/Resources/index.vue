<template>
  <div>
     <div class="">
       <UTabs :items="items" class="mx-auto">
          <template #memo>
              <UCard class="w-full">
      
      dsadad
    </UCard>
          </template>
           <template #account>
              <UCard class="w-full">
      
      dsadad
    </UCard>
          </template>
       </UTabs>
     </div>
    
  </div>
</template>
<script setup lang="ts">
import type { TabsItem } from '@nuxt/ui';

definePageMeta({
  middleware: ['school-middleware'],
  layout: 'school',
});
const items = ref<TabsItem[]>([
  {
    label: 'Account',
    icon: 'i-lucide-user',
    content: 'This is the account content.',
     slot: 'account' as const
  },
  {
    label: 'Memo',
    icon: 'i-lucide-lock',
    content: 'This is the password content.',
    slot: 'memo' as const
  },
  {
    label: 'Template',
    icon: 'i-lucide-lock',
    content: 'This is the password content.',
    slot: 'template' as const
  }
])
</script>