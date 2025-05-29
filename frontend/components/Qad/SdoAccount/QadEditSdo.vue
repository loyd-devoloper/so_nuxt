<template>

  <div>
    <UModal  v-model:open="open"  title="Edit SDO Account">

      <UButton color="success" icon="lucide:edit" label="EDIT" size="sm"  type="button" variant="outline"/>

      <template #body>
        <form ref="newSdoAccount" @submit.prevent="updateSchoolAccountFunc()">
          <main class=" space-y-3">
            <h2 class="font-bold">SDO Details</h2>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField :error="error?.sdo_code && error?.sdo_code[0]" label="SDO Code" required>
                <UInput v-model="sdoCredentials.sdo_code" class="w-full" size="lg" type="number" variant="outline"/>
                <!--            <small v-if="error?.email" class="text-red-500">{{ error?.email[0]}}</small>-->
              </UFormField>
              <UFormField :error="error?.sdo_name && error?.sdo_name[0]" label="SDO Name" required>
                <UInput v-model="sdoCredentials.sdo_name" class="w-full" size="lg" type="text" variant="outline"/>
                <!--            <small v-if="error?.email" class="text-red-500">{{ error?.email[0]}}</small>-->
              </UFormField>

            </div>
            <div  class="grid grid-cols-2 gap-5 ">
              <UFormField :error="error?.sds_name && error?.sds_name[0]" label="SDS Name" required>
                <UInput v-model="sdoCredentials.sds_name" class="w-full" size="lg" type="text" variant="outline"/>
                <!--            <small v-if="error?.email" class="text-red-500">{{ error?.email[0]}}</small>-->
              </UFormField>
              <UFormField :error="error?.asds_name && error?.asds_name[0]" label="ASDS Address" required>
                <UInput v-model="sdoCredentials.asds_name" class="w-full" size="lg" type="text" variant="outline"/>

              </UFormField>
            </div>
            <div  class="grid grid-cols-2 gap-5 ">
              <UFormField :error="error?.status && error?.status[0]" label="Status" required>
                <USelectMenu
                    v-model="sdoCredentials.status"
                    value-key="id"
                    :items="[
                  {
                  label:'Active',
                  id:'active'
                },
                {
                  label:'Inactive',
                 id:'inactive'
                }
            ]"
                    :multiple="false"
                    :required="true" class="w-full" size="lg"/>
              </UFormField>
              <UFormField :error="error?.type && error?.type[0]" label="Type" required>

                <USelectMenu v-model="sdoCredentials.type" :items="['City','Province'
            ]"
                             :multiple="false" class="w-full" size="lg"/>
              </UFormField>
            </div>
            <h2 class="font-bold">Account Details</h2>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField :error="error?.email && error?.email[0]" label="Email Address" required>
                <UInput v-model="sdoCredentials.email" class="w-full" size="lg" type="email" variant="outline"/>
              </UFormField>
              <UFormField :error="error?.password && error?.password[0]" label="Password" hint="Optional" >
                <UInput

                    v-model="sdoCredentials.password" :type="show ? 'text' : 'password'" class="w-full" size="lg"
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
                <!--            <small v-if="error?.email" class="text-red-500">{{ error?.email[0]}}</small>-->
              </UFormField>
            </div>
           <div class="space-x-2 mt-7 flex justify-end">
             <UButton label="Cancel" color="neutral" variant="outline" type="button"  @click="open = false"  />
             <UButton :disabled="isPending" :loading="isPending" color="success"  type="submit">
               Update
             </UButton>
           </div>
          </main>

        </form>
      </template>

    </UModal>

  </div>

</template>
<script lang="ts" setup>
import {useMutation} from '@tanstack/vue-query'
import type {SdoCredentialsType} from "#shared/types/Qad/SdoAccountType";
import {editSdoAccount, storeSdoAccount, updateSdoAccount} from "#shared/API/Qad/SdoAccountApi";
const open = ref<boolean>(false)
const toast = useToast()
const show = ref<boolean>(false)

const queryClient = useQueryClient();

const sdoCredentials = reactive<SdoCredentialsType>({

})
// eslint-disable-next-line vue/prop-name-casing
const props = defineProps<{sdo_account_id?:string | number}>()
const {mutate} = useMutation({
  mutationFn:() => editSdoAccount(props?.sdo_account_id || 0),
  onSuccess: (data) => {
    sdoCredentials.email = data.email
    sdoCredentials.asds_name = data.asds_name
    sdoCredentials.sds_name = data.sds_name
    sdoCredentials.status = data.status
    sdoCredentials.sdo_code = data.sdo_code
    sdoCredentials.type = data.type
    sdoCredentials.sdo_name = data.sdo_name

  }
})
watch(() => open.value,(newOpen) =>{
  if(newOpen) mutate()
})
const {mutate: updateSchoolAccountFunc, error, isPending} = useMutation({
  mutationFn: () => updateSdoAccount(props?.sdo_account_id || 0,sdoCredentials),
  onSuccess:  (data) => {
      sdoCredentials.email = '';
      sdoCredentials.asds_name = '';
      sdoCredentials.sds_name = '';
      sdoCredentials.status = '';
      sdoCredentials.password = '';
      sdoCredentials.type = '';
      sdoCredentials.sdo_code = '';
    sdoCredentials.sdo_name = '';
     queryClient.invalidateQueries({queryKey: ["QAD_SDO_ACCOUNT"]});
    open.value = false;
    toast.add({
      title: data,
      color: 'success',
    })
  },
  onError: (error: any) => {

  },
});

</script>