<template>


  <UModal v-model:open="open" :close="true" title="New Curriculum">
    <UButton color="secondary" icon="basil:add-outline" label="Create Curriculum" type="button" variant="solid"/>
    <template #body>
      <form class=" space-y-3" @submit.prevent="storeSchoolAccountFunc()">

        <div class="grid grid-cols-2 gap-5 ">
          <UFormField :error="error?.school_year_start && error?.school_year_start[0]" label="Start Year" required>
            <UInput
                v-model="curriculumCredential.school_year_start" class="w-full" size="lg" type="number"
                variant="outline"/>
          </UFormField>
          <UFormField :error="error?.school_year_end && error?.school_year_end[0]" label="End Year" required>
            <UInput
                v-model="curriculumCredential.school_year_end" class="w-full" size="lg" type="number"
                variant="outline"/>
          </UFormField>
        </div>
        <div class="grid grid-cols-2 gap-5 ">
          <UFormField
              :error="error?.regional_director && error?.regional_director[0]" label="Regional Director"
              required>
            <UInput
                v-model="curriculumCredential.regional_director" class="w-full" size="lg" type="text"
                variant="outline"/>
          </UFormField>
          <UFormField
              :error="error?.assistant_regional_director && error?.assistant_regional_director[0]"
              label="Assistant Regional Director" required>
            <UInput
                v-model="curriculumCredential.assistant_regional_director" class="w-full" size="lg" type="text"
                variant="outline"/>
          </UFormField>
        </div>
        <UFormField
            :error="error?.is_open_for_so_application && error?.is_open_for_so_application[0]" label="Status"
            required>

          <USelectMenu
              v-model="curriculumCredential.is_open_for_so_application" :items="['Open','Close']" :multiple="false"
              class="w-full" size="lg" value-key="id"/>
        </UFormField>

        <div class="grid grid-cols-2 gap-5 ">
          <UFormField
              :error="error?.open_date && error?.open_date[0]" hint="Optional"
              label="Open Date">
            <UInput
                v-model="curriculumCredential.open_date" class="w-full" size="lg" type="datetime-local"
                variant="outline"/>
          </UFormField>
          <UFormField
              :error="error?.closing_date && error?.closing_date[0]" hint="Optional"
              label="Close Date">
            <UInput
                v-model="curriculumCredential.closing_date" class="w-full" size="lg" type="datetime-local"
                variant="outline"/>
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
import {useMutation} from '@tanstack/vue-query'
import type {CurriculumCredentails} from "#shared/types/Qad/CurriculumType";
import {storeCurriculum} from "#shared/API/Qad/CurriculumManagementApi";

const toast = useToast()
const open = ref<boolean>(false)
const queryClient = useQueryClient();
const curriculumCredential = reactive<CurriculumCredentails>({})

const {mutate: storeSchoolAccountFunc, error, isPending} = useMutation({
  mutationFn: () => storeCurriculum(curriculumCredential),
  onSuccess: (data) => {
    queryClient.invalidateQueries({queryKey: ["QAD_CURRICULUM_MANAGEMENT"]});
    open.value = false;
    toast.add({
      title: data,
      color: 'success',
      icon: 'ooui:success'
    })
  },
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  onError: (err: any) => {

  },
});

</script>