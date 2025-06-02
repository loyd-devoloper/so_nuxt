<template>


  <UModal v-model:open="open" :close="false" :dimdissible="false" :fullscreen="true"  >

    <template #body>
      <form class=" space-y-3" @submit.prevent="storeSchoolBulkAccountFunc()">
        <UAlert
            color="info"
            icon="i-lucide-terminal"
            title=" Please fill up all the required fields"
        />

        <section class="grid grid-cols-7 justify-evenly gap-5 px-10 py-5 ">
          <main class="space-y-2 col-span-3">
            <h2 class="font-bold">School Admin's name</h2>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField :error="error?.admin_last_name && error?.admin_last_name[0]" label="Last Name" required>
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
              <UFormField :error="error?.admin_first_name && error?.admin_first_name[0]" label="First Name" required>
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
            </div>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField :error="error?.admin_middle_name && error?.admin_middle_name[0]" hint="Optional"
                          label="Middle Name">
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
              <UFormField :error="error?.admin_suffix && error?.admin_suffix[0]" hint="Optional" label="Suffix ">
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
            </div>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField :error="error?.admin_email_address  && error?.admin_email_address [0]" hint="Optional"
                          label="Email Address">
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
              <UFormField :error="error?.admin_contact_number && error?.admin_contact_number[0]" hint="Optional"
                          label="Contact Number ">
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
            </div>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField label="Password" required name="password" :error="error?.password && error?.password[0]">
                <UInput
                     size="lg" class="w-full" variant="outline"
                    placeholder="Password" :type="show ? 'text' : 'password'">
                  <template #trailing>
                    <UButton
                        color="neutral" variant="link" size="md" :icon="show ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                        :aria-label="show ? 'Hide password' : 'Show password'" :aria-pressed="show"
                        aria-controls="password"
                        @click="show = !show"/>
                  </template>
                </UInput>

              </UFormField>

            </div>

          </main >
          <USeparator orientation="vertical" class="h-48" />

          <main class="space-y-2 col-span-3" >
            <h2 class="font-bold">School Information</h2>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField :error="error?.school_number && error?.school_number[0]" label="School Number" required>
                <UInput class="w-full" size="md" type="number" variant="outline"/>
              </UFormField>
              <UFormField :error="error?.school_name && error?.school_name[0]" label="School Name" required>
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
            </div>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField :error="error?.school_address && error?.school_address[0]" required
                          label="School Address ">
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
              <UFormField :error="error?.school_head_name && error?.school_head_name[0]" label="School Head Name" required>
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
            </div>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField :error="error?.owner_name  && error?.owner_name [0]" hint="Optional"
                          label="Owner Name">
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
              <UFormField :error="error?.school_email_address && error?.school_email_address[0]" required
                          label="School Email Address">
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
            </div>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField :error="error?.school_contact_number  && error?.school_contact_number [0]" required
                          label="School Contact Number">
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
              <UFormField  required
                          label="SDO Name">
                <UInput class="w-full" size="md" type="text" variant="outline"/>
              </UFormField>
            </div>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField
                  :error="error?.excel_file && error?.excel_file[0]" label="SEC Registration Permit "
                  required>
                <UInput class="w-full" size="md" type="file" variant="outline"
                        @change="(e: Event) => (excelFile = (e.target as HTMLInputElement).files?.[0] || null)"/>
              </UFormField>
              <UFormField
                           label="SEC Registration Permit Expiration"  hint="Optional" >
                <UInput class="w-full" size="md" type="date" variant="outline"/>
              </UFormField>

            </div>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField
                  :error="error?.excel_file && error?.excel_file[0]" label="SEC Registration Permit "
                  required>
                <UInput class="w-full" size="md" type="file" variant="outline"
                        @change="(e: Event) => (excelFile = (e.target as HTMLInputElement).files?.[0] || null)"/>
              </UFormField>
              <UFormField
                  label="SEC Registration Permit Expiration"  hint="Optional" >
                <UInput class="w-full" size="md" type="date" variant="outline"/>
              </UFormField>

            </div>

            <div class="grid grid-cols-2 gap-5 ">
              <UFormField
                  :error="error?.excel_file && error?.excel_file[0]" label="Mayor's Permit"
                  required>
                <UInput class="w-full" size="md" type="file" variant="outline"
                        @change="(e: Event) => (excelFile = (e.target as HTMLInputElement).files?.[0] || null)"/>
              </UFormField>
              <UFormField
                  label="Mayor's Permit Expiration"  hint="Optional" >
                <UInput class="w-full" size="md" type="date" variant="outline"/>
              </UFormField>

            </div>
          </main>

          <main class=" col-span-7">

            <h2 class="font-bold whitespace-nowrap"> Program's Offered/Strand</h2>

            <div class="grid grid-cols-5 gap-10  w-full ">
              <UFormField :error="error?.sdo_id && error?.sdo_id[0]" label="School Division Office" required>

                <USelectMenu label-key="sdo_name" value-key="id"
                             :multiple="false" class="w-full" size="lg"/>
              </UFormField>
              <UFormField :error="error?.sdo_id && error?.sdo_id[0]" label="School Division Office" required>

                <USelectMenu label-key="sdo_name" value-key="id"
                             :multiple="false" class="w-full" size="lg"/>
              </UFormField>
              <UFormField :error="error?.sdo_id && error?.sdo_id[0]" label="School Division Office" required>

                <USelectMenu label-key="sdo_name" value-key="id"
                             :multiple="false" class="w-full" size="lg"/>
              </UFormField>
            </div>
          </main>
        </section>


        <div class="space-x-2 mt-7 flex justify-end">

          <UButton :disabled="isPending" :loading="isPending" color="secondary" type="submit">
            Submit
          </UButton>
        </div>
      </form>
    </template>


  </UModal>


</template>
<script lang="ts" setup>

import {storeSchoolBulkAccount} from "#shared/API/Qad/SchoolAccountApi";
import {useMutation} from '@tanstack/vue-query'
const show = ref(false)
const toast = useToast()
const open = ref<boolean>(true)
const queryClient = useQueryClient();
const excelFile = ref<File | null>(null)

const {mutate: storeSchoolBulkAccountFunc, error, isPending} = useMutation({
  mutationFn: () => storeSchoolBulkAccount(excelFile),
  onSuccess: (data) => {
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