<template>
    <div class="space-y-10">
        <div class="grid grid-cols-5 gap-5">
            <UCard class="col-span-3 shadow-lg rounded-lg">
    <div class="space-y-3 text-sm">
      <div class="grid grid-cols-2 gap-4">
        <div class="space-y-2">
          <p class="font-semibold text-gray-500">School:</p>
          <p class="font-medium text-gray-800">{{ data?.school_info?.school_name || 'Not specified' }}</p>
        </div>
        
        <div class="space-y-2">
          <p class="font-semibold text-gray-500">School Number:</p>
          <p class="font-medium text-gray-800">{{ data?.school_info?.school_number  || 'Not specified' }}</p>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div class="space-y-2">
          <p class="font-semibold text-gray-500">Status:</p>
          <UBadge :label="data?.status"  />
        </div>
        
        <div class="space-y-2">
          <p class="font-semibold text-gray-500">Applied Track:</p>
          <p class="font-medium text-gray-800">{{ data?.applied_track || 'Not specified' }}</p>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div class="space-y-2">
          <p class="font-semibold text-gray-500">Applied Strand:</p>
          <p class="font-medium text-gray-800">{{ data?.applied_strand || 'Not specified' }}</p>
        </div>
        
        <div class="space-y-2">
          <p class="font-semibold text-gray-500">Applied Specialization:</p>
          <p class="font-medium text-gray-800">{{ data?.applied_specialization || 'Not specified' }}</p>
        </div>
      </div>
    </div>

  </UCard>
            <UCard class="col-span-2 h-fit">
                <UStepper :items="items" :default-value="stepperDefault" color="success" class="w-full" :ui="{
                  title: 'text-xs',
                  indicator:'text-xs'
                }" />
                <div class="space-x-1 grid grid-cols-3 items-center gap-2 mt-10">
                    <QadTransactionValidateStudent  :search-value="search" :application-data="data" school_id="1" />
                    <UButton color="neutral" icon="ix:log" variant="solid" label="Activity Log" size="sm" />
                    <QadTransactionQadPreviewSo :application_id="route?.params?.application_id || ''">
                       <UButton color="warning" icon="fontisto:preview" label="Generate So" size="sm"  type="button" variant="solid"/>
                      </QadTransactionQadPreviewSo>
                </div>
            </UCard>
        </div>
        <QadTransactionStudentTable />


    </div>
</template>
<script setup lang="ts">
import type { StepperItem } from '@nuxt/ui'
import { debounce } from 'lodash';
import { fetchApplication } from '~/shared/API/Qad/TransactionApi';
definePageMeta({
    middleware: ['qad-middleware'],
    layout: 'adminlayout',
});
const search = ref<string>('')
const route = useRoute();
watch(()=>search.value,(newVal)=>{

    console.log(newVal)
})
const stepperDefault = computed(() =>{
  if(data.value?.is_form_checked && !data.value?.is_evaluation_checked && !data.value?.is_review_checked && !data.value?.is_approve_checked) return 0;
   if(data.value?.is_form_checked && data.value?.is_evaluation_checked && !data.value?.is_review_checked && !data.value?.is_approve_checked) return 1;
    if(data.value?.is_form_checked && data.value?.is_evaluation_checked && data.value?.is_review_checked && !data.value?.is_approve_checked) return 2;
     if(data.value?.is_form_checked && data.value?.is_evaluation_checked && data.value?.is_review_checked && data.value?.is_approve_checked) return 3;
  return -1;
})
const queryClient = useQueryClient();
const { data } = useQuery({
    queryKey: ['QAD_TRANSACTION_APPLICATION', route?.params?.application_id],
    queryFn: () => fetchApplication(route?.params?.application_id || '',search.value)
})

const items = ref<StepperItem[]>([
    {
        title: 'Form 9',


    },
    {
        title: 'Evaluator',


    },
    {
        title: 'Checker',

    },
    {
        title: 'Approver',

    }
])
</script>