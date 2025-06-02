<template>


  <UModal v-model:open="open" :close="true" title="Edit Curriculum">
    <UButton color="success" icon="lucide:edit" label="EDIT" size="sm"  type="button" variant="outline"/>
    <template #body>
      <form class=" space-y-3" @submit.prevent="storeSchoolAccountFunc()">

        <div class="grid grid-cols-2 gap-5 ">
          <UFormField :error="error?.school_year_start && error?.school_year_start[0]" label="Start Year" required>
            <UInput v-model="curriculumCredential.school_year_start" class="w-full" size="lg" type="number" variant="outline"/>
          </UFormField>
          <UFormField :error="error?.school_year_end && error?.school_year_end[0]" label="End Year" required>
            <UInput v-model="curriculumCredential.school_year_end" class="w-full" size="lg" type="number" variant="outline"/>
          </UFormField>
        </div>
        <div  class="grid grid-cols-2 gap-5 ">
          <UFormField :error="error?.regional_director && error?.regional_director[0]" label="Regional Director" required>
            <UInput v-model="curriculumCredential.regional_director" class="w-full" size="lg" type="text" variant="outline"/>
          </UFormField>
          <UFormField :error="error?.assistant_regional_director && error?.assistant_regional_director[0]" label="Assistant Regional Director" required>
            <UInput v-model="curriculumCredential.assistant_regional_director" class="w-full" size="lg" type="text" variant="outline"/>
          </UFormField>
        </div>
        <UFormField :error="error?.is_open_for_so_application && error?.is_open_for_so_application[0]" label="Status" required>

          <USelectMenu
v-model="curriculumCredential.is_open_for_so_application" :items="['Open','Close']"  value-key="id"
                       :multiple="false" class="w-full" size="lg"/>
        </UFormField>

        <div class="grid grid-cols-2 gap-5 ">
          <UFormField
:error="error?.open_date && error?.open_date[0]" label="Open Date"
                      hint="Optional">
            <UInput v-model="curriculumCredential.open_date" class="w-full" size="lg" type="datetime-local" variant="outline"/>
          </UFormField>
          <UFormField
              :error="error?.closing_date && error?.closing_date[0]" label="Close Date"
              hint="Optional">
            <UInput v-model="curriculumCredential.closing_date" class="w-full" size="lg" type="datetime-local" variant="outline"/>
          </UFormField>

        </div>
        <div class="space-x-2 mt-7 flex justify-end">
          <UButton label="Cancel" color="neutral" variant="outline" type="button"  @click="open = false"  />
          <UButton :disabled="isPending" :loading="isPending" color="success"  type="submit">
            Update
          </UButton>
        </div>
      </form>
    </template>


  </UModal>


</template>
<script lang="ts" setup>
import {useMutation} from '@tanstack/vue-query'
import type {CurriculumCredentails} from "#shared/types/Qad/CurriculumType";
import {editCurriculum, updateCurriculum} from "#shared/API/Qad/CurriculumManagementApi";
const toast = useToast()
const open = ref<boolean>(false)
const queryClient = useQueryClient();
const curriculumCredential = reactive<CurriculumCredentails>({})

// eslint-disable-next-line vue/prop-name-casing
const props = defineProps<{curriculum_id?:string | number}>()
const {mutate} = useMutation({
  mutationFn:() => editCurriculum(props?.curriculum_id || 0),
  onSuccess: (data) => {
    curriculumCredential.school_year_start = data.school_year_start
    curriculumCredential.school_year_end = data.school_year_end
    curriculumCredential.is_open_for_so_application = data.is_open_for_so_application
    curriculumCredential.regional_director = data.regional_director
    curriculumCredential.assistant_regional_director = data.assistant_regional_director
    curriculumCredential.open_date = data.open_date
    curriculumCredential.closing_date = data.closing_date

  }
})
watch(() => open.value,(newOpen) =>{
  if(newOpen) mutate()
})
const {mutate: storeSchoolAccountFunc, error, isPending} = useMutation({
  mutationFn: () => updateCurriculum(props?.curriculum_id || 0,curriculumCredential),
  onSuccess:  (data) => {
    queryClient.invalidateQueries({queryKey: ["QAD_CURRICULUM_MANAGEMENT"]});
    open.value = false;
    toast.add({
      title: data,
      color: 'success',
      icon:'ooui:success'
    })
  },
  onError: (err: any) => {

  },
});

</script>