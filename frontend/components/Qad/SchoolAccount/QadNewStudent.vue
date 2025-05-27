<template>

   <form id="storeSchoolAccount" @submit.prevent="storeSchoolAccountFunc()">
     <UModal :close="true" title="New School Account">
       <UButton color="secondary" icon="basil:add-outline" label="Create School Account" type="button" variant="solid"/>
       <template #body>
         <main class=" space-y-3">
           <h2 class="font-bold">School Details</h2>
           <div class="grid grid-cols-2 gap-5 ">
             <UFormField label="School ID" required>
               <UInput v-model="schoolCredentials.school_number" class="w-full" size="lg" type="number" variant="outline"/>
               <!--            <small v-if="error?.email" class="text-red-500">{{ error?.email[0]}}</small>-->
             </UFormField>
             <UFormField label="School Name" required>
               <UInput v-model="schoolCredentials.school_name" class="w-full" size="lg" type="text" variant="outline"/>
               <!--            <small v-if="error?.email" class="text-red-500">{{ error?.email[0]}}</small>-->
             </UFormField>
           </div>
           <UFormField label="School Address" required>
             <UInput v-model="schoolCredentials.school_address" class="w-full" size="lg" type="text" variant="outline"/>
             <!--            <small v-if="error?.email" class="text-red-500">{{ error?.email[0]}}</small>-->
           </UFormField>
           <UFormField label="School Division Office" required>
             <UInput v-model="schoolCredentials.sdo_id" class="w-full" size="lg" variant="outline"/>
             <!--            <small v-if="error?.email" class="text-red-500">{{ error?.email[0]}}</small>-->
           </UFormField>
           <h2 class="font-bold">School Details</h2>
           <div class="grid grid-cols-2 gap-5 ">
             <UFormField label="Email Address" required>
               <UInput v-model="schoolCredentials.email_address" class="w-full" size="lg" type="email" variant="outline"/>
               <!--            <small v-if="error?.email" class="text-red-500">{{ error?.email[0]}}</small>-->
             </UFormField>
             <UFormField label="Password" required>
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
               <!--            <small v-if="error?.email" class="text-red-500">{{ error?.email[0]}}</small>-->
             </UFormField>
           </div>
         </main>
       </template>
       <template #footer>
         <div class="flex justify-end  w-full">
           <UButton
               form-id="storeSchoolAccount"
               :disabled="isPending" class="w-full flex justify-center" color="secondary" size="lg" type="submit" >
             <UIcon v-if="isPending" class="animate-spin" name="mingcute:loading-line"/>
             Submit</UButton>
         </div>
       </template>
       <UButton
           form-id="storeSchoolAccount"
           :disabled="isPending" class="w-full flex justify-center" color="secondary" size="lg" type="submit" >
         <UIcon v-if="isPending" class="animate-spin" name="mingcute:loading-line"/>
         Submit</UButton>
     </UModal>
   </form>

</template>
<script lang="ts" setup>
import type {SchoolCredentialsType} from '~/shared/types/Qad/SchoolAccount';
import {storeSchoolAccount} from "#shared/API/Qad/SchoolAccountApi";
import {useMutation} from '@tanstack/vue-query'
const show = ref<boolean>(false)
const queryClient = useQueryClient();
const schoolCredentials = reactive<SchoolCredentialsType>({})
const {mutate: storeSchoolAccountFunc, isPending} = useMutation({
  mutationFn: () =>storeSchoolAccount(schoolCredentials),
  onSuccess: async () => {
    await queryClient.invalidateQueries({queryKey: ["schoolList"]});

  },
  onError: (err: any) => {

  },
});

</script>