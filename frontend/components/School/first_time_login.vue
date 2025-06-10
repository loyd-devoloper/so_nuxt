<template>
  <UModal v-model:open="open" :close="false" :dismissible="false" class="max-w-screen-lg">
    <template #body>
      <form class="w-full" @submit.prevent="firstTimeLoginFunc()">
        <UAlert
            v-if="authStore.authUser?.is_first_time_login"
            color="info"
            icon="i-lucide-terminal"
            title=" Please fill up all the required fields"
        />
        <UAlert
            v-else
            color="warning"
            description="Please be informed that the processing of your request/application is currently on hold as we await the QAD Verification. We kindly ask for your patience and understanding during this time. Rest assured that we will notify you as soon as the verification is completed. Thank you!"
            icon="meteor-icons:bullhorn"
            title="NOTICE"
            variant="solid"
        />
        <UStepper ref="stepper" :items="items" class="pt-10">
          <template #admin>
            <Placeholder class="aspect-video">

              <main class="space-y-2 col-span-3">
                <div v-if="error" class="bg-red-500 p-4 text-white rounded mb-6">

                  <div v-for="err in error" :key="err" class="grid grid-cols-3 text-sm">
                    <li v-for="(message, index) in err" :key="index">
                      {{ message[0] }}
                    </li>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-5 ">
                  <UFormField :error="error?.admin_last_name && error?.admin_last_name[0]" label="Last Name" required>
                    <UInput
v-model="firstTimeLoginCredential.admin_last_name" class="w-full" size="md" type="text"
                            variant="outline"/>
                  </UFormField>
                  <UFormField
:error="error?.admin_first_name && error?.admin_first_name[0]" label="First Name"
                              required>
                    <UInput
v-model="firstTimeLoginCredential.admin_first_name" class="w-full" size="md" type="text"
                            variant="outline"/>
                  </UFormField>
                </div>
                <div class="grid grid-cols-2 gap-5 ">
                  <UFormField
:error="error?.admin_middle_name && error?.admin_middle_name[0]" hint="Optional"
                              label="Middle Name">
                    <UInput
v-model="firstTimeLoginCredential.admin_middle_name" class="w-full" size="md" type="text"
                            variant="outline"/>
                  </UFormField>
                  <UFormField :error="error?.admin_suffix && error?.admin_suffix[0]" hint="Optional" label="Suffix ">
                    <UInput
v-model="firstTimeLoginCredential.admin_suffix" class="w-full" size="md" type="text"
                            variant="outline"/>
                  </UFormField>
                </div>
                <div class="grid grid-cols-2 gap-5 ">
                  <UFormField
:error="error?.admin_email_address  && error?.admin_email_address [0]" required
                              label="Email Address">
                    <UInput
v-model="firstTimeLoginCredential.admin_email_address" class="w-full" size="md" type="text"
                            variant="outline"/>
                  </UFormField>
                  <UFormField
:error="error?.admin_contact_number && error?.admin_contact_number[0]" required
                              label="Contact Number ">
                    <UInput
v-model="firstTimeLoginCredential.admin_contact_number" class="w-full" size="md" type="text"
                            variant="outline"/>
                  </UFormField>
                </div>
                <div class="grid grid-cols-2 gap-5 ">
                  <UFormField :error="error?.password && error?.password[0]" label="Password" name="password" required>
                    <UInput
                        v-model="firstTimeLoginCredential.password"
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

                  <div v-for="err in error" :key="err" class="grid grid-cols-3 text-sm">
                    <li v-for="(message, index) in err" :key="index">
                      {{ message[0] }}
                    </li>
                  </div>
                </div>
                <div class="grid grid-cols-2 gap-5 ">
                  <UFormField :error="error?.school_number && error?.school_number[0]" label="School Number" required>
                    <UInput
v-model="firstTimeLoginCredential.school_number" class="w-full" disabled size="md"
                            type="number" variant="outline"/>
                  </UFormField>
                  <UFormField :error="error?.school_name && error?.school_name[0]" label="School Name" required>
                    <UInput
v-model="firstTimeLoginCredential.school_name" class="w-full" size="md" type="text"
                            variant="outline"/>
                  </UFormField>
                </div>
                <div class="grid grid-cols-2 gap-5 ">
                  <UFormField
:error="error?.school_address && error?.school_address[0]" label="School Address "
                              required>
                    <UInput
v-model="firstTimeLoginCredential.school_address" class="w-full" size="md" type="text"
                            variant="outline"/>
                  </UFormField>
                  <UFormField
:error="error?.school_head_name && error?.school_head_name[0]" label="School Head Name"
                              required>
                    <UInput
v-model="firstTimeLoginCredential.school_head_name" class="w-full" size="md" type="text"
                            variant="outline"/>
                  </UFormField>
                </div>
                <div class="grid grid-cols-2 gap-5 ">
                  <UFormField
:error="error?.owner_name  && error?.owner_name [0]" hint="Optional"
                              label="Owner Name">
                    <UInput
v-model="firstTimeLoginCredential.owner_name" class="w-full" size="md" type="text"
                            variant="outline"/>
                  </UFormField>
                  <UFormField
:error="error?.school_email_address && error?.school_email_address[0]"
                              label="School Email Address"
                              required>
                    <UInput
v-model="firstTimeLoginCredential.school_email_address" class="w-full" size="md" type="text"
                            variant="outline"/>
                  </UFormField>
                </div>
                <div class="grid grid-cols-2 gap-5 ">
                  <UFormField
:error="error?.school_contact_number  && error?.school_contact_number [0]"
                              label="School Contact Number"
                              required>
                    <UInput
v-model="firstTimeLoginCredential.school_contact_number" class="w-full" size="md"
                            type="text" variant="outline"/>
                  </UFormField>
                  <UFormField
label="SDO Name"
                              required>
                    <UInput
v-model="firstTimeLoginCredential.sdo_id" class="w-full" disabled size="md" type="text"
                            variant="outline"/>
                  </UFormField>
                </div>
                <div class="grid grid-cols-2 gap-5 ">
                  <UFormField
                      :error="error?.sec_permit && error?.sec_permit[0]" label="SEC Registration Permit "
                      required>
                    <UInput
class="w-full" size="md" type="file" variant="outline"
accept="application/pdf,application/vnd.ms-excel"
                            @change="(e: Event) => (firstTimeLoginCredential.sec_permit = (e.target as HTMLInputElement).files?.[0] || null)"/>
                    <small>{{ firstTimeLoginCredential?.sec_permit?.name }}</small>
                  </UFormField>
                  <UFormField
                      hint="Optional" label="SEC Registration Permit Expiration">
                    <UInput
v-model="firstTimeLoginCredential.sec_expiration_date" class="w-full" size="md" type="date"
                            variant="outline"/>

                  </UFormField>

                </div>
                <div class="grid grid-cols-2 gap-5 ">
                  <UFormField
                      :error="error?.shs_provisional_permit && error?.shs_provisional_permit[0]" label="SHS Provisional Permit"
                      required>
                    <UInput
class="w-full" size="md" type="file" variant="outline"
                    accept="application/pdf,application/vnd.ms-excel"
                            @change="(e: Event) => (firstTimeLoginCredential.shs_provisional_permit = (e.target as HTMLInputElement).files?.[0] || null)"/>
                    <small>{{ firstTimeLoginCredential?.shs_provisional_permit?.name }}</small>
                  </UFormField>
                  <UFormField
                      hint="Optional" label="SEC Registration Permit Expiration">
                    <UInput
v-model="firstTimeLoginCredential.shs_provisional_expiration_date" class="w-full" size="md"
                            type="date" variant="outline"/>
                  </UFormField>

                </div>

                <div class="grid grid-cols-2 gap-5 ">
                  <UFormField
                      :error="error?.mayors_permit && error?.mayors_permit[0]" label="Mayor's Permit"

                     
                      required>
                    <UInput
class="w-full" size="md" type="file" variant="outline"
                     accept="application/pdf,application/vnd.ms-excel"
                            @change="(e: Event) => (firstTimeLoginCredential.mayors_permit = (e.target as HTMLInputElement).files?.[0] || null)"/>
                    <small>{{ firstTimeLoginCredential?.mayors_permit?.name }}</small>
                  </UFormField>
                  <UFormField
                      hint="Optional" label="Mayor's Permit Expiration">
                    <UInput
v-model="firstTimeLoginCredential.mayors_permit_expiration_date" class="w-full" size="md"
                            type="date" variant="outline"/>
                  </UFormField>

                </div>
              </main>
            </Placeholder>
          </template>
          <template #programs>
            <Placeholder class="aspect-video">
              <main class=" col-span-7">

                <div v-if="error" class="bg-red-500 p-4 text-white rounded mb-6">

                  <div v-for="err in error" :key="err" class="grid grid-cols-3 text-sm">
                    <li v-for="(message, index) in err" :key="index">
                      {{ message[0] }}
                    </li>
                  </div>
                </div>
                <form class="grid grid-cols-5 gap-10 items-center w-full " @submit.prevent="submitProgram">
                  <UFormField :error="error?.sdo_id && error?.sdo_id[0]" label="School Division Office" required>

                    <USelect
                        v-model="schoolPrograms.track" :default-value="''" :eager-validation="true"
                        :items="data?.programs"
                        :multiple="false" class="w-full"
                        label-key="track_key" required size="lg" value-key="id"/>
                  </UFormField>
                  <UFormField :error="error?.sdo_id && error?.sdo_id[0]" label="School Division Office" required>

                    <USelect
                        v-model="schoolPrograms.strand"
                        :items="strandArr"
                        :multiple="false"
                        class="w-full"
                        label-key="name" required size="lg" value-key="name"/>
                  </UFormField>
                  <UFormField :error="error?.sdo_id && error?.sdo_id[0]" hint="Optional" label="School Division Office">

                    <USelect
                        v-model="schoolPrograms.specialization"
                        :items="specializationArr"
                        :multiple="true" class="w-full" size="lg"/>
                  </UFormField>
                  <UButton class="h-fit w-fit" color="success" icon="lucide:plus" label="EDIT" size="sm" type="submit">
                    add
                  </UButton>
                </form>
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
                        v-for="(track,index) in firstTimeLoginCredential.program_offered" :key="track.id"
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
                      </td>
                    </tr>

                    </tbody>
                  </table>
                </div>
              </main>
            </Placeholder>
          </template>
        </UStepper>

        <div class="flex gap-2 justify-between mt-10">
          <UButton
              :disabled="!stepper?.hasPrev"
              leading-icon="i-lucide-arrow-left"
              @click="stepper?.prev()"
          >
            Prev
          </UButton>
          <UButton
              color="neutral"
              leading-icon="material-symbols:logout-rounded"
              @click="authStore.logout('School')"
          >
            Logout
          </UButton>

          <UButton
              v-if="stepper?.hasNext"
              trailing-icon="i-lucide-arrow-right"
              @click="stepper?.next()"
          >
            Next
          </UButton>
          <UButton
v-if="!stepper?.hasNext && authStore.authUser?.is_first_time_login === 1" :disabled="isPending"
                   :loading="isPending" color="secondary" type="submit">
            Submit
          </UButton>
        </div>
      </form>
    </template>


  </UModal>


</template>
<script lang="ts" setup>

import {useMutation} from '@tanstack/vue-query'
import {fetchLatestCurriculum, firstTimeLogin} from "#shared/API/School/FirstTimeLoginApi";
import type {StepperItem} from '@nuxt/ui'
import type {FirstTimeLoginType} from "#shared/types/School/firstTimeLoginType";
import {useAuthStore} from "~/stores/AuthStore";


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
const authStore = useAuthStore()
const firstTimeLoginCredential = reactive<FirstTimeLoginType>({
  school_email_address: authStore.authUser?.school_email_address ?? '',
  admin_contact_number: authStore.authUser?.admin_contact_number ?? '',
  admin_email_address: authStore.authUser?.admin_email_address ?? '',
  admin_first_name: authStore.authUser?.admin_first_name ?? '',
  admin_last_name: authStore.authUser?.admin_last_name ?? '',
  admin_middle_name: authStore.authUser?.admin_middle_name ?? '',
  admin_suffix: authStore.authUser?.admin_suffix ?? '',
  owner_name: authStore.authUser?.owner_name ?? '',
  school_address: authStore.authUser?.school_address ?? '',
  school_contact_number: authStore.authUser?.school_contact_number ?? '',
  school_head_name: authStore.authUser?.school_head_name ?? '',
  school_name: authStore.authUser?.school_name ?? '',
  school_number: authStore.authUser?.school_number ?? '',
  sdo_id: authStore.authUser?.sdo_information?.sdo_name ?? '',
  program_offered: authStore.authUser?.program_offered ?? '',

})

const show = ref(false)
const open = ref<boolean>(authStore.authUser?.status !== 'approved')

// eslint-disable-next-line @typescript-eslint/no-explicit-any
const schoolPrograms = reactive<{ track?: string, track_key?: string, strand?: any, specialization?: any }>({
  track: '',
  track_key: '',
  strand: '',
  specialization: []
})
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const strandArr = ref<{[key: string]: any;}[]>([]);
const specializationArr = ref([]);
watch(() => schoolPrograms.track, (newTrack) => {
  schoolPrograms.strand = '';
  schoolPrograms.specialization = [];
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const newData = data.value?.programs.find((value:{[key: string]: any;}) => {
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
const submitProgram = () => {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const checkIfExist = firstTimeLoginCredential.program_offered.find((track: any) => {
    return track.track === schoolPrograms.track_key && track.strand === schoolPrograms.strand;
  });
  if (checkIfExist === undefined) {
    firstTimeLoginCredential.program_offered.push({
      track: schoolPrograms.track_key,
      strand: schoolPrograms.strand,
      specialization: schoolPrograms.specialization,
    })
  } else {
    checkIfExist.specialization = schoolPrograms.specialization;
  }

}
const removeTrack = (index: number) => {
  firstTimeLoginCredential.program_offered.splice(index, 1);

}
const removeSpecialization = (trackIndex: number, specIndex: number) => {
  firstTimeLoginCredential.program_offered[trackIndex].specialization.splice(specIndex, 1);
};

const {data} = useQuery({
  queryKey: ['FIRST_TIME_LOGIN'],
  queryFn: () => fetchLatestCurriculum()
})
const {mutate: firstTimeLoginFunc, error, isPending} = useMutation({
  mutationFn: () => firstTimeLogin(firstTimeLoginCredential),
  onSuccess: (data) => {
    authStore.authUser.is_first_time_login = 0;
    toast.add({
      title: data,
      color: 'success',
        icon:'ooui:success'
    })
  },
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  onError: (_err: any) => {

  },
});

</script>