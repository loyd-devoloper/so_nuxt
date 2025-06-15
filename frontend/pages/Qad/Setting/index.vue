<template>
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
</template>
<script setup lang="ts">
import type { TabsItem } from '@nuxt/ui';
definePageMeta({
    middleware: ['qad-middleware'],
    layout: 'adminlayout',
});
const items = ref<TabsItem[]>([
   {
    label: 'Information',
    icon: 'i-lucide-lock',
    slot: 'template' as const,

  },
  {
    label: 'Password',
    icon: 'i-lucide-user',
   
    slot: 'attachment' as const
  },
  {
    label: 'Signature',
    icon: 'i-lucide-lock',

    slot: 'signature' as const
  },
 
])
</script>