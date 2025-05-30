<template>


  <UModal v-model:open="open" :close="true" title="New School Account">
    <UButton color="secondary" icon="basil:add-outline" label="Create School Account" type="button" variant="solid"/>
    <template #body>
      <form class=" space-y-3" @submit.prevent="storeSchoolAccountFunc()">
        <h2 class="font-bold">School Details</h2>
        <div class="grid grid-cols-2 gap-5 ">
          <UFormField :error="error?.school_number && error?.school_number[0]" label="School Number" required>
            <UInput v-model="schoolCredentials.school_number" class="w-full" size="lg" type="number" variant="outline"/>
          </UFormField>
          <UFormField :error="error?.school_name && error?.school_name[0]" label="School Name" required>
            <UInput v-model="schoolCredentials.school_name" class="w-full" size="lg" type="text" variant="outline"/>
          </UFormField>
        </div>
        <UFormField :error="error?.school_address && error?.school_address[0]" label="School Address" required>
          <UInput v-model="schoolCredentials.school_address" class="w-full" size="lg" type="text" variant="outline"/>
        </UFormField>
        <UFormField :error="error?.sdo_id && error?.sdo_id[0]" label="School Division Office" required>

          <USelectMenu v-model="schoolCredentials.sdo_id" :items="data" label-key="sdo_name" value-key="id"
                       :multiple="false" class="w-full" size="lg"/>
        </UFormField>
        <h2 class="font-bold">School Details</h2>
        <div class="grid grid-cols-2 gap-5 ">
          <UFormField :error="error?.school_email_address && error?.school_email_address[0]" label="Email Address"
                      required>
            <UInput v-model="schoolCredentials.school_email_address" class="w-full" size="lg" type="email" variant="outline"/>
          </UFormField>
          <UFormField :error="error?.password && error?.password[0]" label="Password" required>
            <UInput
                v-model="schoolCredentials.password" :type="show ? 'text' : 'password'" class="w-full" size="lg"
                variant="outline">
              <template #trailing>
                <UButton
                    :aria-label="show ? 'Hide password' : 'Show password'"
                    :aria-pressed="show"
                    :icon="show ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                    aria-controls="password"
                    color="neutral"
                    size="sm"
                    variant="link"
                    @click="show = !show"
                />

              </template>
            </UInput>

          </UFormField>
        </div>
        <div class="space-x-2 mt-7 flex justify-end">
          <UButton color="neutral" label="Cancel" type="button" variant="outline" @click="open = false"/>
          <UButton :disabled="isPending" :loading="isPending" color="secondary" type="submit">
            Create
          </UButton>
        </div>
      </form>
    </template>


  </UModal>


</template>
<script lang="ts" setup>
import type {SchoolCredentialsType} from '~/shared/types/Qad/SchoolAccount';
import {sdoList, storeSchoolAccount} from "#shared/API/Qad/SchoolAccountApi";
import {useMutation} from '@tanstack/vue-query'
const toast = useToast()
const show = ref<boolean>(false)
const open = ref<boolean>(false)
const queryClient = useQueryClient();
const schoolCredentials = reactive<SchoolCredentialsType>({})
const {data} = useQuery({
  queryKey:['QAD_SCHOOL_ACCOUNT_SDOS'],
  queryFn:() => sdoList()
})
const {mutate: storeSchoolAccountFunc, error, isPending} = useMutation({
  mutationFn: () => storeSchoolAccount(schoolCredentials),
  onSuccess:  (data) => {
     queryClient.invalidateQueries({queryKey: ["QAD_SCHOOL_ACCOUNT"]});
    open.value = false;
    toast.add({
      title: data,
      color: 'success',
    })
  },
  onError: (err: any) => {

  },
});

</script>