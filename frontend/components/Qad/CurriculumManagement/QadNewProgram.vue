<template>


  <UModal v-model:open="open" :close="true" title="New Curriculum" >
    <UButton color="secondary" icon="basil:add-outline" label="Create Curriculum" type="button" variant="solid"/>
    <template #body>
      <form class=" space-y-3" @submit.prevent="storeProgramFunc()">

        <div class="grid grid-cols-2 gap-5 ">
          <UFormField :error="error?.track && error?.track[0]" label="Track" required>
            <UInput
                v-model="programData.track" class="w-full" size="lg" type="text"
                variant="outline"/>
          </UFormField>
          <UFormField :error="error?.track_key && error?.track_key[0]" label="Track key" required>
            <UInput
                v-model="programData.track_key" class="w-full" size="lg" type="text"
                variant="outline"/>
          </UFormField>
        </div>
        <UButton icon="mdi:plus" size="sm" color="primary" variant="solid" @click="() =>{
            programData?.strand.push({name: '',values: []})
        }">Strand</UButton>

        <div v-for="(strnd,index) in programData.strand" :key="strnd">
          <UFormField :error="error?.track_key && error?.track_key[0]" label="Strand" required>
            <UInput
                v-model="programData.strand[index].name"  class="w-full" size="lg" type="text"
                variant="outline"/>
          </UFormField>
          <div class="flex justify-end">
            <UButton size="xs" color="success" icon="mdi:plus" @click="() =>{
               programData.strand[index].values.push('')
            }"> Specialization</UButton>

            <UIcon
                v-if=" programData.strand.length > 1"
              name="ic:baseline-delete" class="text-red-500 cursor-pointer" size="1.25rem" title="Delete Strand" @click="() =>{
              programData.strand.splice(index,1)
            }"/>

          </div>
          <div v-for="(spec,indexSpec) in strnd.values" :key="indexSpec" class="px-10 flex items-end gap-2">
            <UFormField :error="error?.track_key && error?.track_key[0]" label="Specialization" required class="w-full">
              <UInput
                  v-model="programData.strand[index].values[indexSpec]"
                  class="w-full" size="lg" type="text"
                  variant="outline"/>
            </UFormField>
            <UIcon
                name="ic:baseline-delete" class="text-red-500 cursor-pointer" size="1.25rem" title="Delete Specialization" @click="() =>{
              programData.strand[index].values.splice(index,1)
            }"/>
          </div>
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
import type {ProgramsType} from "#shared/types/Qad/CurriculumType";
import {storeCurriculum, storeProgram} from "#shared/API/Qad/CurriculumManagementApi";


const toast = useToast()
const open = ref<boolean>(false)
const queryClient = useQueryClient();
const programData = reactive<ProgramsType>({
  strand:[{
    name:'',
    values: [ ]
  }]
})

const {mutate: storeProgramFunc, error, isPending} = useMutation({
  mutationFn: () => storeProgram(programData),
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