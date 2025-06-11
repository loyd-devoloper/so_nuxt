<template>
  <div>

    <div class="grid grid-cols-4 gap-5">
      <UCard>
        <UButton icon="mdi-light:calendar" size="lg" variant="subtle" />
        <h2 class="text-muted text-xs mt-1">School Year</h2>
        <h1 class="text-sm font-bold">{{ moment().subtract(1, 'y').format('YYYY') + ' - ' + moment().format('YYYY') }}
        </h1>
      </UCard>
      <UCard>
        <UButton icon="fluent:form-28-regular" size="lg" variant="subtle" />
        <h2 class="text-muted text-xs mt-1">Application</h2>
        <h1 class="text-sm font-bold">1
        </h1>
      </UCard>
      <UCard>
        <UButton icon="mdi:account-pending-outline" size="lg" variant="subtle" />
        <h2 class="text-muted text-xs mt-1">Pending Student(s)</h2>
        <h1 class="text-sm font-bold">2
        </h1>
      </UCard>
      <UCard>
        <UButton icon="mingcute:user-x-line" size="lg" variant="subtle" />
        <h2 class="text-muted text-xs mt-1">Rejected Student(s)</h2>
        <h1 class="text-sm font-bold">4
        </h1>
      </UCard>
      <UCard>
        <UButton icon="mdi:user-off-outline" size="lg" variant="subtle" />
        <h2 class="text-muted text-xs mt-1">With Deficiency Student(s)</h2>
        <h1 class="text-sm font-bold">7
        </h1>
      </UCard>
      <UCard>
        <UBadge icon="material-symbols-light:order-approve-outline" size="lg" variant="subtle" />
        <h2 class="text-muted text-xs mt-1">Approved Application</h2>
        <h1 class="text-sm font-bold">2
        </h1>
      </UCard>

    </div>

    <div class="grid grid-cols-2 gap-5 mt-5">
      <UCard :ui="{
        body:'bg-gray-100'
      }">
        <template #header >
        <div class="flex items-center gap-2">
           <UIcon name="mingcute:horn-line" size="1.5rem"/> Announcement
        </div>
        </template>

        <h1 class="text-red-500">No Data Found</h1>

        
      </UCard>
      <UCard :ui="{
        body:'bg-gray-100'
      }">
        <template #header>
          On Process Application
        </template>

       <h1 class="text-red-500">No Data Found</h1>

       
      </UCard>
    </div>
  </div>
</template>
<script setup lang="ts">
import moment from 'moment';
definePageMeta({
  middleware: ['school-middleware'],
  layout: 'school',
});

</script>