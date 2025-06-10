<template>


  <UModal v-model:open="open" :close="true" title="Change Password">
    <UButton color="error" icon="basil:add-outline" label="Change Password" type="button" size="sm" variant="outline" />
    <template #body>
      <form class=" space-y-3" @submit.prevent="storeSchoolBulkAccountFunc()">

        <UFormField :error="error?.password && error?.password[0]" label="Change Password" required>
          <UInput v-model="passwordData.password" class="w-full" size="lg" :type="show ? 'text' : 'password'" variant="outline">
            <template #trailing>
              <UButton
color="neutral" variant="link" size="sm" :icon="show ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                :aria-label="show ? 'Hide password' : 'Show password'" :aria-pressed="show" aria-controls="password"
                type="button" @click="show = !show" />
            </template>
          </UInput>
        </UFormField>
          <UFormField :error="error?.password_confirmation && error?.password_confirmation[0]" label="Password Confirmation" required>
          <UInput v-model="passwordData.password_confirmation" class="w-full" size="lg" :type="show1 ? 'text' : 'password'"  variant="outline">
            <template #trailing>
              <UButton
color="neutral" variant="link" size="sm" :icon="show1 ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                :aria-label="show1 ? 'Hide password' : 'Show password'" :aria-pressed="show" aria-controls="password"
                type="button" @click="show1 = !show1" />
            </template>
          </UInput>
        </UFormField>


        <div class="space-x-2 mt-7 flex justify-end">
          <UButton color="neutral" label="Cancel" type="button" variant="outline" @click="open = false" />
          <UButton :disabled="isPending" :loading="isPending" color="error" type="submit">
            Update
          </UButton>
        </div>
      </form>
    </template>


  </UModal>


</template>
<script lang="ts" setup>

import { changePassword } from "#shared/API/Qad/SchoolAccountApi";
import { useMutation } from '@tanstack/vue-query'
const toast = useToast()
const open = ref<boolean>(false)


const show = ref<boolean>(false);
const show1 = ref<boolean>(false);

const passwordData = reactive<{password:string,password_confirmation:string}>({
  password: '',
  password_confirmation: '',
})
const props = defineProps<{ schoolId: string | number }>()
const { mutate: storeSchoolBulkAccountFunc, error, isPending } = useMutation({
  mutationFn: () => changePassword(passwordData,props.schoolId || 0),
  onSuccess: (data) => {
    open.value = false;

    toast.add({
      title: data,
      color: 'success',
      icon: 'ooui:success'
    })
  },
  // eslint-disable-next-line @typescript-eslint/no-explicit-any, @typescript-eslint/no-unused-vars
  onError: (err: any) => {

  },
});

</script>