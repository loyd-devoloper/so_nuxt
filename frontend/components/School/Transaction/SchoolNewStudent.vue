<!-- eslint-disable @typescript-eslint/no-explicit-any -->
<!-- eslint-disable @typescript-eslint/no-explicit-any -->
<template>

  <div>
    <UModal v-model:open="open" :dismissible="false" :modal="true" title="New Student">
      <template #body>
        <form ref="newSdoAccount" @submit.prevent="storeApplicationFunc()">
          <main class=" space-y-1">

            <UFormField :error="error?.first_name && error?.first_name[0]" label="First Name" required>
              <UInput

                    v-model="studentData.first_name"
                      class="w-full"
                      size="lg" type="text" variant="outline"/>
            </UFormField>
           <UFormField :error="error?.middle_name && error?.middle_name[0]" label="Middle Name" hint="Optional">
              <UInput
                v-model="studentData.middle_name"
                      class="w-full"
                      size="lg" type="text" variant="outline"/>
            </UFormField>

               <UFormField :error="error?.last_name && error?.last_name[0]" label="Last Name" required>
              <UInput
                v-model="studentData.last_name"
                      class="w-full"
                      size="lg" type="text" variant="outline"/>
            </UFormField>
                <UFormField :error="error?.suffix && error?.suffix[0]" label="Suffix" hint="Optional">
              <UInput
                v-model="studentData.suffix"
                      class="w-full"
                      size="lg" type="text" variant="outline"/>
            </UFormField>
       <UFormField :error="error?.lrn && error?.lrn[0]" label="LRN" required>
              <UInput
                v-model="studentData.lrn"
                      class="w-full"
                      size="lg" type="number" variant="outline"/>
            </UFormField>
            <div class="space-x-2 mt-7 flex justify-end">
              <UButton color="neutral" label="Cancel" type="button" variant="outline" @click="open = false"/>
              <UButton :disabled="isPending" :loading="isPending" color="secondary" type="submit">
                Submit
              </UButton>
            </div>
          </main>

        </form>
      </template>
      <UButton
color="secondary" icon="basil:add-outline" label="New Student" size="md" type="button"
               variant="solid"/>
    </UModal>

  </div>

</template>
<script lang="ts" setup>
import {useMutation} from '@tanstack/vue-query'
import type { StudentType} from "#shared/types/School/SchoolApplicationType";
import { StoreNewSoStudents} from "#shared/API/School/TransactionApi";

const open = ref<boolean>(false)
const toast = useToast()
const route = useRoute();
const queryClient = useQueryClient();
const studentData = reactive<StudentType>({})

const {mutate: storeApplicationFunc, error, isPending} = useMutation({
  mutationFn: () => StoreNewSoStudents(studentData,route?.params?.application_id ),
  onSuccess: (data) => {
    queryClient.invalidateQueries({queryKey:['SCHOOL_TRANSACTION_STUDENTS']})
    open.value = false;

    studentData.first_name = '';
    studentData.last_name = '';
    studentData.middle_name = '';
    studentData.suffix = '';
    studentData.lrn = '';

    toast.add({
      title: data,
      color: 'success',
      icon: 'ooui:success'
    })
  },

  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  onError: (_error: any) => {

  },
});

</script>