<template>

  <div>
    <UModal v-model:open="open" :dismissible="false" :modal="true" title="Student Bulk">
      <template #body>
        <form ref="newSdoAccount" @submit.prevent="storeApplicationFunc()">
          <main class=" space-y-1">

            <UFormField :error="error?.excel_file && error?.excel_file[0]" hint="Excel File" label="Students File"
              required>
              <UInput
                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                class="w-full" size="lg" type="file" variant="outline"
                @change="(e: Event) => (excel_file = (e.target as HTMLInputElement).files?.[0] || null)" />

            </UFormField>
            <div class="space-x-2 mt-7 flex justify-end">
              <UButton color="neutral" label="Cancel" type="button" variant="outline" @click="open = false" />
              <UButton :disabled="isPending" :loading="isPending" color="secondary" type="submit">
                Submit
              </UButton>
            </div>
          </main>

        </form>
      </template>
      <UButton color="error" icon="basil:add-outline" label="Student Bulk" size="md" type="button" variant="outline" />
    </UModal>

  </div>

</template>
<script lang="ts" setup>
import { useMutation } from '@tanstack/vue-query'

import { storeBulkSoStudents } from "#shared/API/School/TransactionApi";

const open = ref<boolean>(false)
const toast = useToast()
const route = useRoute();
const queryClient = useQueryClient();
const excel_file = ref<File | null>(null)

const { mutate: storeApplicationFunc, error, isPending } = useMutation({
  mutationFn: () => storeBulkSoStudents(excel_file.value, route?.params?.application_id),
  onSuccess: (data) => {
    queryClient.invalidateQueries({ queryKey: ['SCHOOL_TRANSACTION_STUDENTS'] })
    open.value = false;
    excel_file.value = null;

    if (data.skipped && data.skipped.length > 0) {

      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      const messages = data.skipped.map((item: any) => {
        // Remove "Row X: " prefix
        return item.replace(/^Row \d+: /, '')
      })
      toast.add({
        title: `${data.skipped.length} Duplicate Students Skipped`,
        description: messages.join('\n'),

        color: 'error'
      });
    }
    toast.add({
      title: data.success,
      color: 'success',
      icon: 'ooui:success'
    })
  },
  // eslint-disable-next-line @typescript-eslint/no-unused-vars, @typescript-eslint/no-explicit-any
  onError: (error: any) => {

  },
});

</script>