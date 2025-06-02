<template>


  <UModal v-model:open="open" :close="true" title="New School Account">
    <UButton color="secondary" icon="basil:add-outline" label="Create Bulk School Account" type="button" variant="solid"/>
    <template #body>
      <form class=" space-y-3" @submit.prevent="storeSchoolBulkAccountFunc()">

  {{error}}
          <UFormField
:error="error?.excel_file && error?.excel_file[0]" label="Excel File"
                      required>
            <UInput  class="w-full" size="lg" type="file" variant="outline" @change="(e: Event) => (excelFile = (e.target as HTMLInputElement).files?.[0] || null)"/>
          </UFormField>

        <div class="space-x-2 mt-7 flex justify-end">
          <UButton color="neutral" label="Cancel" type="button" variant="outline" @click="open = false"/>
          <UButton :disabled="isPending" :loading="isPending" color="secondary" type="submit">
            Submit
          </UButton>
        </div>
      </form>
    </template>


  </UModal>


</template>
<script lang="ts" setup>

import { storeSchoolBulkAccount} from "#shared/API/Qad/SchoolAccountApi";
import {useMutation} from '@tanstack/vue-query'
const toast = useToast()
const open = ref<boolean>(false)
const queryClient = useQueryClient();
const excelFile = ref<File | null>(null)

const {mutate: storeSchoolBulkAccountFunc, error, isPending} = useMutation({
  mutationFn: () => storeSchoolBulkAccount(excelFile),
  onSuccess:  (data) => {
    queryClient.invalidateQueries({queryKey: ["QAD_SCHOOL_ACCOUNT"]});
    open.value = false;
    toast.add({
      title: data,
      color: 'success',
      icon:'ooui:success'
    })
  },
  onError: (err: any) => {

  },
});

</script>