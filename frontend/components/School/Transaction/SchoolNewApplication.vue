<template>

  <div>
    <UModal v-model:open="open" :dismissible="false" :modal="true" title="New Application">
      <template #body>
        <form ref="newSdoAccount" @submit.prevent="storeApplicationFunc()">
          <main class=" space-y-1">

            <UFormField :error="error?.status && error?.status[0]" label="School Year" required>
              <USelectMenu
                  v-model="school_year"
                  :filter-fields="['track', 'strand']"
                  :items="activeCurriculum"
                  :loading="status === 'pending'"

                  :multiple="false"
                  :required="true" class="w-full" size="md">
                <template #leading="{ modelValue }">

                  <span v-if="modelValue">
                        <strong>{{ modelValue.school_year_start }} - {{ modelValue.school_year_end }}</strong>
                  </span>
                </template>

                <template #item-label="{ item }">
                  <strong>{{ item.school_year_start }} - {{ item.school_year_end }}</strong>
                </template>
              </USelectMenu>
            </UFormField>
            <UFormField :error="error?.applied_track && error?.applied_track[0]" label="Track" required :help="specialization.length === 0 ?' No available specializations found.' : ''">
              <USelectMenu

                  :filter-fields="['track', 'strand']"
                  :items="authStore.authUser.program_offered"
                  :loading="status === 'pending'"
                  v-model="track"
                  :multiple="false"
                  :required="true" class="w-full" size="md">
                <template #leading="{ modelValue }">

                  <span v-if="modelValue">
                      <strong>{{ modelValue?.track }}</strong> ~ <span class="text-muted"> {{ modelValue?.strand }}</span>
                  </span>
                </template>

                <template #item-label="{ item }">
                  <strong>{{ item.track }}</strong> ~
                  <span class="text-muted">
                {{ item.strand }}
              </span>
                </template>

              </USelectMenu>
            </UFormField>

            <UFormField v-show="specialization.length > 0" :error="error?.applied_specialization && error?.applied_specialization[0]" label="Specialization" required>
              <USelectMenu
                  v-model="applicationData.applied_specialization"
                  :items="specialization"
                  :multiple="true"
                  :required="true"
                  class="w-full" size="md" value-key="id"/>
            </UFormField>
            <UFormField :error="error?.attestation_file && error?.attestation_file[0]" label="Attestation File" required>
              <UInput
accept="application/pdf,application/vnd.ms-excel"
                      class="w-full"
                      size="lg" type="file" variant="outline"/>
            </UFormField>
            <UFormField :error="error?.form9_file && error?.form9_file[0]" label="Form 9" required>
              <UInput  accept="application/pdf,application/vnd.ms-excel"
                      class="w-full"
                      size="lg" type="file" variant="outline"/>
            </UFormField>
            <UFormField :error="error?.students_file && error?.students_file[0]" hint="Excel File" label="Students File" required>
              <UInput
                      accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                      class="w-full"
                      size="lg"
                      type="file" variant="outline"/>
            </UFormField>
            <UFormField :error="error?.graduation_date && error?.graduation_date[0]" label="Graduation Date" required>
              <UInput v-model="applicationData.graduation_date" class="w-full" size="lg" type="date" variant="outline"/>
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
      <UButton color="secondary" icon="basil:add-outline" label="Create Sdo Account" size="md" type="button"
               variant="solid"/>
    </UModal>

  </div>

</template>
<script lang="ts" setup>
import {useMutation} from '@tanstack/vue-query'
import {storeSdoAccount} from "#shared/API/Qad/SdoAccountApi";
import {useAuthStore} from "~/stores/AuthStore";
import type {NewApplicationType} from "#shared/types/School/SchoolApplicationType";
import {fetchActiveCurriculum, storeApplication} from "#shared/API/School/TransactionApi";

const open = ref<boolean>(false)
const toast = useToast()
const authStore = useAuthStore()
const queryClient = useQueryClient();
const school_year = ref<any>({});
const track = ref('');
const specialization = ref<string[] | number[]>([]);
const applicationData = reactive<NewApplicationType>({
  applied_specialization: []
})
const {data:activeCurriculum} = useQuery({
  queryKey: ['SCHOOL_ACTIVE_CURRICULA'],
  queryFn: () => fetchActiveCurriculum()
})
const {mutate: storeApplicationFunc, error, isPending} = useMutation({
  mutationFn: () => storeApplication(applicationData),
  onSuccess: (data) => {

    open.value = false;
    toast.add({
      title: data,
      color: 'success',
      icon: 'ooui:success'
    })
  },
  onError: (error: any) => {

  },
});
watch(() => track.value, (newData) => {


  applicationData.applied_strand = newData?.strand;
  applicationData.applied_track = newData?.track;
  specialization.value = newData?.specialization;

})
watch(() => school_year.value,(newValue) =>{
  applicationData.curriculum_id = newValue.id;
})
</script>