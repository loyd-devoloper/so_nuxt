<template>
  <div>
    <UCard class="bg-white">
      <UTabs :items="items" class="mx-auto " default-value="2" variant="link">
          <template #attachment>
          
            <SchoolResourcesAttachement/>
           
   
        </template>
        <template #memo>
         

            <SchoolResourcesMemo/>
         
        </template>
       <template #template>
        

            <SchoolResourcesTemplate/>
        
        </template>
      </UTabs>
    </UCard>

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
    label: 'Template',
    icon: 'i-lucide-lock',
    slot: 'template' as const,

  },
  {
    label: 'Attachment',
    icon: 'i-lucide-user',
   
    slot: 'attachment' as const
  },
  {
    label: 'Memo',
    icon: 'i-lucide-lock',

    slot: 'memo' as const
  },
 
])
</script>