<template>

  <div>
    <UModal v-model:open="open" :dismissible="false" :modal="true" title="New Application">
      <template #body>
        <form ref="newSdoAccount" @submit.prevent="storeApplicationFunc()">
          <main class=" space-y-1">

            <UFormField :error="error?.curriculum_id && error?.curriculum_id[0]" label="School Year" required>
              <USelectMenu v-model="school_year" :filter-fields="['track', 'strand']" :items="activeCurriculum"
                :multiple="false" :required="true" class="w-full" size="md">
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
            <UFormField :error="error?.applied_track && error?.applied_track[0]" label="Track" required
              :help="specialization.length === 0 ? ' No available specializations found.' : ''">
              <USelectMenu v-model="track" :filter-fields="['track', 'strand']"
                :items="authStore.authUser.program_offered" :multiple="false" :required="true" class="w-full" size="md">
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

            <UFormField v-show="specialization.length > 0"
              :error="error?.applied_specialization && error?.applied_specialization[0]" label="Specialization"
              required>
              <USelectMenu v-model="applicationData.applied_specialization" :items="specialization" :multiple="true"
                :required="true" class="w-full" size="md" value-key="id" />
            </UFormField>
            <UFormField :error="error?.attestation_file && error?.attestation_file[0]" label="Attestation File"
              required>
              <UInput accept="application/pdf,application/vnd.ms-excel" class="w-full" size="lg" type="file"
                variant="outline"
                @change="(e: Event) => (applicationData.attestation_file = (e.target as HTMLInputElement).files?.[0] || null)" />
            </UFormField>
            <UFormField :error="error?.form_9_file && error?.form_9_file[0]" label="Form 9" required>
              <UInput accept="application/pdf,application/vnd.ms-excel" class="w-full" size="lg" type="file"
                variant="outline"
                @change="(e: Event) => (applicationData.form_9_file = (e.target as HTMLInputElement).files?.[0] || null)" />
            </UFormField>
            <UFormField :error="error?.students_file && error?.students_file[0]" hint="Excel File" label="Students File"
              required>
              <UInput
                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                class="w-full" size="lg" type="file" variant="outline"
                @change="(e: Event) => (applicationData.students_file = (e.target as HTMLInputElement).files?.[0] || null)" />
            </UFormField>
            <UFormField :error="error?.graduation_date && error?.graduation_date[0]" label="Graduation Date" required>
              <UInput v-model="applicationData.graduation_date" class="w-full" size="lg" type="date"
                variant="outline" />
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
      <UButton color="secondary" icon="basil:add-outline" label="New Application" size="md" type="button"
        variant="solid" />
    </UModal>

  </div>

</template>
<script lang="ts" setup>
import { useMutation } from '@tanstack/vue-query'
import { useAuthStore } from "~/stores/AuthStore";
import type { NewApplicationType } from "#shared/types/School/SchoolApplicationType";
import { fetchActiveCurriculum, storeApplication } from "#shared/API/School/TransactionApi";

const open = ref<boolean>(false)
const toast = useToast()
const authStore = useAuthStore()
const queryClient = useQueryClient();
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const school_year = ref<{ [key: string]: any; } | null>(null);
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const track = ref<{ [key: string]: any; } | null>(null);
const specialization = ref<string[] | number[]>([]);
const applicationData = reactive<NewApplicationType>({
  applied_specialization: []
})
const { data: activeCurriculum } = useQuery({
  queryKey: ['SCHOOL_ACTIVE_CURRICULA'],
  queryFn: () => fetchActiveCurriculum()
})
const { mutate: storeApplicationFunc, error, isPending } = useMutation({
  mutationFn: () => storeApplication(applicationData),
  onSuccess: (data) => {
    queryClient.invalidateQueries({ queryKey: ['SCHOOL_TRANSACTION'] })
    open.value = false;
    applicationData.applied_specialization = [];
    applicationData.applied_strand = '';
    applicationData.applied_track = '';
    applicationData.curriculum_id = '';
    applicationData.attestation_file = null;
    applicationData.form_9_file = null;
    applicationData.students_file = null;
    applicationData.graduation_date = '';
    school_year.value = null;
    track.value = null;
    specialization.value = [];
    toast.add({
      title: data,
      color: 'success',
      icon: 'ooui:success'
    })
  },
  // eslint-disable-next-line @typescript-eslint/no-unused-vars, @typescript-eslint/no-explicit-any
  onError: (error: any) => {

  },
});
watch(() => track.value, (newData) => {


  applicationData.applied_strand = newData?.strand ?? '';
  applicationData.applied_track = newData?.track;
  applicationData.applied_specialization = [];
  specialization.value = newData?.specialization ?? [];


})
watch(() => school_year.value, (newValue) => {
  applicationData.curriculum_id = newValue?.id;
})
</script>