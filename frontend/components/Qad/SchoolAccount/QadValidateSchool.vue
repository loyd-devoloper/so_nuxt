<template>
  <UModal v-model:open="open" :close="true" :dismissible="false" :ui="{

    content: 'max-w-screen-2xl'
  }" title="Validate School Account">
    <UButton color="success" icon="lucide:edit" :label="label ?? 'Validate'" size="sm" type="button" variant="outline"/>
    <template #body>
      <form class="max-w-screen-2xl" @submit.prevent="updateSchoolAccountFunc()">
        <section class="grid grid-cols-2 gap-10">
          <main>
           <UFormField label="Select Document" >
             <USelectMenu v-model="selectedPdf" :items="schoolAccountData?.account_attachments" class="w-full"
                          label-key="document_name" value-key="id"/>
           </UFormField>
            <iframe :src="attachmentData" class="w-full h-[60svh] mt-5"></iframe>
          </main>
          <UStepper ref="stepper" :items="items">
            <template #admin>
              <Placeholder class="aspect-video">
                <main class="space-y-2">
                  <div v-if="error" class="bg-red-500 p-4 text-white rounded mb-6">

                    <div v-for="err in error" :key="err" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 text-sm">
                      <li v-for="(message, index) in err" :key="index">
                        {{ message[0] }}
                      </li>
                    </div>
                  </div>
                  <div class="grid grid-cols-2 gap-5 ">
                    <UFormField :error="error?.admin_last_name && error?.admin_last_name[0]" label="Last Name" required>
                      <UInput v-model="schoolCredentails.admin_last_name" class="w-full" size="md" type="text"
                              variant="outline"/>
                    </UFormField>
                    <UFormField :error="error?.admin_first_name && error?.admin_first_name[0]" label="First Name"
                                required>
                      <UInput v-model="schoolCredentails.admin_first_name" class="w-full" size="md" type="text"
                              variant="outline"/>
                    </UFormField>
                  </div>
                  <div class="grid grid-cols-2 gap-5 ">
                    <UFormField :error="error?.admin_middle_name && error?.admin_middle_name[0]" hint="Optional"
                                label="Middle Name">
                      <UInput v-model="schoolCredentails.admin_middle_name" class="w-full" size="md" type="text"
                              variant="outline"/>
                    </UFormField>
                    <UFormField :error="error?.admin_suffix && error?.admin_suffix[0]" hint="Optional" label="Suffix ">
                      <UInput v-model="schoolCredentails.admin_suffix" class="w-full" size="md" type="text"
                              variant="outline"/>
                    </UFormField>
                  </div>
                  <div class="grid grid-cols-2 gap-5 ">
                    <UFormField :error="error?.admin_email_address  && error?.admin_email_address [0]" hint="Optional"
                                label="Email Address">
                      <UInput v-model="schoolCredentails.admin_email_address" class="w-full" size="md"
                              type="text"
                              variant="outline"/>
                    </UFormField>
                    <UFormField :error="error?.admin_contact_number && error?.admin_contact_number[0]" hint="Optional"
                                label="Contact Number ">
                      <UInput v-model="schoolCredentails.admin_contact_number" class="w-full" size="md"
                              type="text"
                              variant="outline"/>
                    </UFormField>
                  </div>
                  <div class="grid grid-cols-2 gap-5 ">
                    <UFormField :error="error?.password && error?.password[0]" hint="optional" label="Password"
                                name="password">
                      <UInput
                          v-model="schoolCredentails.password"
                          :type="show ? 'text' : 'password'" class="w-full" placeholder="Password"
                          size="lg" variant="outline">
                        <template #trailing>
                          <UButton
                              :aria-label="show ? 'Hide password' : 'Show password'" :aria-pressed="show"
                              :icon="show ? 'i-lucide-eye-off' : 'i-lucide-eye'" aria-controls="password"
                              color="neutral" size="md"
                              variant="link"
                              @click="show = !show"/>
                        </template>
                      </UInput>

                    </UFormField>

                  </div>

                </main>

              </Placeholder>
            </template>
            <template #school>
              <Placeholder class="aspect-video">
                <main class="space-y-2 col-span-3">
                  <div v-if="error" class="bg-red-500 p-4 text-white rounded mb-6">

                    <div v-for="err in error" :key="err" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 text-sm">
                      <li v-for="(message, index) in err" :key="index">
                        {{ message[0] }}
                      </li>
                    </div>
                  </div>
                  <div class="grid grid-cols-2 gap-5 ">
                    <UFormField :error="error?.school_number && error?.school_number[0]" label="School Number" required>
                      <UInput v-model="schoolCredentails.school_number" class="w-full" disabled size="md"
                              type="number" variant="outline"/>
                    </UFormField>
                    <UFormField :error="error?.school_name && error?.school_name[0]" label="School Name" required>
                      <UInput v-model="schoolCredentails.school_name" class="w-full" size="md" type="text"
                              variant="outline"/>
                    </UFormField>
                  </div>
                  <div class="grid grid-cols-2 gap-5 ">
                    <UFormField :error="error?.school_address && error?.school_address[0]" label="School Address "
                                required>
                      <UInput v-model="schoolCredentails.school_address" class="w-full" size="md" type="text"
                              variant="outline"/>
                    </UFormField>
                    <UFormField :error="error?.school_head_name && error?.school_head_name[0]" label="School Head Name"
                                required>
                      <UInput v-model="schoolCredentails.school_head_name" class="w-full" size="md" type="text"
                              variant="outline"/>
                    </UFormField>
                  </div>
                  <div class="grid grid-cols-2 gap-5 ">
                    <UFormField :error="error?.owner_name  && error?.owner_name [0]" hint="Optional"
                                label="Owner Name">
                      <UInput v-model="schoolCredentails.owner_name" class="w-full" size="md" type="text"
                              variant="outline"/>
                    </UFormField>
                    <UFormField :error="error?.school_email_address && error?.school_email_address[0]"
                                label="School Email Address"
                                required>
                      <UInput v-model="schoolCredentails.school_email_address" class="w-full" size="md"
                              type="text"
                              variant="outline"/>
                    </UFormField>
                  </div>
                  <div class="grid grid-cols-2 gap-5 ">
                    <UFormField :error="error?.school_contact_number  && error?.school_contact_number [0]"
                                label="School Contact Number"
                                required>
                      <UInput v-model="schoolCredentails.school_contact_number" class="w-full" size="md"
                              type="text" variant="outline"/>
                    </UFormField>
                    <UFormField label="SDO Name"
                                required>
                      <UInput v-model="schoolCredentails.sdo_id" class="w-full" disabled size="md" type="text"
                              variant="outline"/>
                    </UFormField>
                  </div>

                </main>
              </Placeholder>
            </template>
            <template #programs>
              <Placeholder class="aspect-video">
                <main class=" col-span-7">

                  <div v-if="error" class="bg-red-500 p-4 text-white rounded mb-6">

                    <div v-for="err in error" :key="err" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 text-sm">
                      <li v-for="(message, index) in err" :key="index">
                        {{ message[0] }}
                      </li>
                    </div>
                  </div>

                  <div class="p-10">
                    <table class="w-full  text-xs text-left rtl:text-right text-gray-500">
                      <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                      <tr>
                        <th class="px-6 py-3 " scope="col">
                          <div class="flex items-center gap-2">
                            Track
                          </div>
                        </th>
                        <th class="px-6 py-3" scope="col">
                          <div class="flex items-center gap-2">
                            Strand
                          </div>
                        </th>
                        <th class="px-6 py-3" scope="col">
                          Specialization
                        </th>

                        <th class="px-6 py-3" scope="col">
                          Action
                        </th>
                      </tr>
                      </thead>
                      <tbody>

                      <tr
                          v-for="(track,index) in schoolCredentails.program_offered" :key="track.id"
                          class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
                        <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" scope="row">
                          {{ track?.track }}
                        </th>
                        <td class="px-6 py-4">
                          {{ track?.strand }}
                        </td>
                        <td class="px-6 py-4">
                          <li v-for="(spec,key) in track?.specialization" :key="spec">{{ spec }}
                            <UIcon
                                class="text-red-500 cursor-pointer" name="mdi:close"
                                @click="removeSpecialization(index,key)"/>

                          </li>
                        </td>


                        <td class="px-6 py-4">
                          <UButton icon="iconamoon:trash-light" variant="ghost" @click="removeTrack(index)"/>

                          <!--                       <UIcon class="text-red-500 cursor-pointer" name="iconamoon:trash-light"-->
                          <!--                                                 @click="removeSpecialization(index,key)"/>-->
                        </td>
                      </tr>

                      </tbody>
                    </table>
                  </div>

                </main>
              </Placeholder>
            </template>
          </UStepper>
        </section>
        <div class="flex gap-2 justify-between mt-10">
          <UButton
              :disabled="!stepper?.hasPrev"
              leading-icon="i-lucide-arrow-left"
              @click="stepper?.prev()"
          >
            Prev
          </UButton>
          <UButton
              v-if="stepper?.hasNext"
              trailing-icon="i-lucide-arrow-right"
              @click="stepper?.next()"
          >
            Next
          </UButton>
          <UButton v-if="!stepper?.hasNext" :disabled="isPending"
                   :loading="isPending" color="success" type="submit">
            Update
          </UButton>
        </div>
      </form>
    </template>


  </UModal>


</template>
<script lang="ts" setup>

import {useMutation} from '@tanstack/vue-query'
import {fetchLatestCurriculum} from "#shared/API/School/FirstTimeLoginApi";
import type {StepperItem} from '@nuxt/ui'
import type {FirstTimeLoginType} from "#shared/types/School/firstTimeLoginType";
import {editSchoolAccount, updateSchoolAccount, viewAttachment} from "#shared/API/Qad/SchoolAccountApi";

const items: StepperItem[] = [
  {
    title: 'School Admin\'s name',
    slot: 'admin' as const,


  }, {
    title: 'School Information',
    slot: 'school' as const,


  }, {
    title: 'Program\'s Offered/Strand',
    slot: 'programs' as const,

  }
]
const stepper = useTemplateRef('stepper')
const toast = useToast()
const queryClient = useQueryClient();
const schoolCredentails = reactive<FirstTimeLoginType>({
  program_offered: [],


})
const show = ref(false)
const open = ref<boolean>(false)
const schoolPrograms = reactive<{ track: string, track_key: string, strand: string, specialization?: string[] }>({
  track: '',
  track_key: '',
  strand: '',
  specialization: []
})
const strandArr = ref([]);
const specializationArr = ref([]);
watch(() => schoolPrograms.track, (newTrack) => {
  schoolPrograms.strand = '';
  schoolPrograms.specialization = [];
  const newData = data.value?.programs.find((value) => {
    return value.id === newTrack

  })
  strandArr.value = newData?.strand;
  schoolPrograms.track_key = newData?.track_key;
})
watch(() => schoolPrograms.strand, (newStrand) => {
  specializationArr.value = [];
  // schoolPrograms.strand = '';
  if (newStrand) {
    const newData = strandArr.value.find((value) => {
      return value.name === newStrand

    })

    specializationArr.value = newData?.values ?? [];
  }
})
// eslint-disable-next-line vue/prop-name-casing
const props = defineProps<{ school_id?: string | number,label?: string | number }>()
const {mutate, data: schoolAccountData} = useMutation({
  mutationFn: () => editSchoolAccount(props.school_id || 0),
  onSuccess: (data) => {
    schoolCredentails.school_email_address = data.school_email_address;
    schoolCredentails.admin_contact_number = data.admin_contact_number;
    schoolCredentails.admin_email_address = data.admin_email_address;
    schoolCredentails.admin_first_name = data.admin_first_name;
    schoolCredentails.admin_last_name = data.admin_last_name;
    schoolCredentails.admin_middle_name = data.admin_middle_name;
    schoolCredentails.admin_suffix = data.admin_suffix;
    schoolCredentails.owner_name = data.owner_name;
    schoolCredentails.school_address = data.school_address;
    schoolCredentails.school_contact_number = data.school_contact_number;
    schoolCredentails.school_head_name = data.school_head_name;
    schoolCredentails.school_name = data.school_name;
    schoolCredentails.school_number = data.school_number;
    schoolCredentails.sdo_id = data.sdo_information?.sdo_name;
    schoolCredentails.program_offered = data.program_offered;
  }
})
const selectedPdf = ref<string>('');
const {mutate: viewAttachmentFunc, data: attachmentData} = useMutation({
  mutationFn: () => viewAttachment(selectedPdf.value),

})
watch(() => selectedPdf.value, () => {
  viewAttachmentFunc()
})
watch(() => open.value, (newOpen) => {
  if (newOpen) mutate()
})
const removeTrack = (index: number) => {
  schoolCredentails.program_offered.splice(index, 1);

}
const removeSpecialization = (trackIndex: number, specIndex: number) => {
  schoolCredentails.program_offered[trackIndex].specialization.splice(specIndex, 1);
};

const {data} = useQuery({
  queryKey: ['FIRST_TIME_LOGIN'],
  queryFn: () => fetchLatestCurriculum()
})
const {mutate: updateSchoolAccountFunc, error, isPending} = useMutation({
  mutationFn: () => updateSchoolAccount(props.school_id || 0,schoolCredentails),
  onSuccess: (data) => {
    queryClient.invalidateQueries({queryKey: ["QAD_SCHOOL_ACCOUNT"]});
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