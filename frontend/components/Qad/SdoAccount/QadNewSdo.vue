<template>

  <div>
    <UModal :close="true" title="New School Account">

      <UButton color="secondary" icon="basil:add-outline" label="Create Sdo Account" type="button" variant="solid"/>
     <template #content>
        
     </template>
      <template #body>
        <form ref="newSdoAccount" @submit.prevent="storeSchoolAccountFunc()">
          <main class=" space-y-3">
            <h2 class="font-bold">SDO Details</h2>
            <div class="grid grid-cols-2 gap-5 ">

              <UFormField :error="error?.sdo_name && error?.sdo_name[0]" label="SDO NAME" required>
                <UInput v-model="sdoCredentials.sdo_name" class="w-full" size="lg" type="number" variant="outline"/>
                <!--            <small v-if="error?.email" class="text-red-500">{{ error?.email[0]}}</small>-->
              </UFormField>
              <UFormField :error="error?.sds_name && error?.sds_name[0]" label="SDS Name" required>
                <UInput v-model="sdoCredentials.sds_name" class="w-full" size="lg" type="text" variant="outline"/>
                <!--            <small v-if="error?.email" class="text-red-500">{{ error?.email[0]}}</small>-->
              </UFormField>
            </div>
            <UFormField :error="error?.asds_name && error?.asds_name[0]" label="ASDS Address" required>
              <UInput v-model="sdoCredentials.asds_name" class="w-full" size="lg" type="text" variant="outline"/>

            </UFormField>
            <UFormField :error="error?.status && error?.status[0]" label="Status" required>
              <USelectMenu
                  v-model="sdoCredentials.status"
                  :items="[
                {
                  label:'Active',
                  id:1
                },
                {
                  label:'Inactive',
                  id:0
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
            <h2 class="font-bold">Account Details</h2>
            <div class="grid grid-cols-2 gap-5 ">
              <UFormField :error="error?.email && error?.email[0]" label="Email Address" required>
                <UInput v-model="sdoCredentials.email" class="w-full" size="lg" type="email" variant="outline"/>
              </UFormField>
              <UFormField :error="error?.password && error?.password[0]" label="Password" required>
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
            <UButton :disabled="isPending" :loading="isPending" class="w-full flex justify-center" type="submit">
              Submit
            </UButton>
          </main>

        </form>
      </template>
    </UModal>
  </div>

</template>
<script lang="ts" setup>
import {useMutation} from '@tanstack/vue-query'
import type {SdoCredentialsType} from "#shared/types/Qad/SdoAccountType";
import {storeSdoAccount} from "#shared/API/Qad/SdoAccountApi";

const show = ref<boolean>(false)
const queryClient = useQueryClient();
const form = useTemplateRef('newSdoAccount')
const sdoCredentials = reactive<SdoCredentialsType>({})
const {mutate: storeSchoolAccountFunc, error, isPending} = useMutation({
  mutationFn: () => storeSdoAccount(sdoCredentials),
  onSuccess: async () => {
    // await queryClient.invalidateQueries({queryKey: ["schoo"]});

  },
  onError: (error: any) => {

  },
});

</script>