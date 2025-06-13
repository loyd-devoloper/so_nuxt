<template>
    <UModal v-model:open="open" :close="true" :dismissible="false" :ui="{

        content: 'max-w-screen-2xl'
    }" title="Validate So Application">
        <UButton color="success" icon="lucide:edit" :label="label ?? 'Validate'" size="sm" type="button"
            variant="outline" />
        <template #body>
              
            <form class="max-w-screen-2xl" @submit.prevent="updateSchoolAccountFunc()">
                <section class="grid grid-cols-2 gap-10">
                    <main>
                        <UFormField label="Select Document">
                            <USelectMenu v-model="selectedPdf" :items="cleanName"
                                class="w-full" label-key="document_name" value-key="id" />
                        </UFormField>
                        <iframe :src="attachmentData" class="w-full h-[60svh] mt-5" />
                    </main>
                    <section>

                        <div class="flex justify-between items-center gap-2">
                            <UButton>APPROVED</UButton>

                            <UInput icon="i-lucide-search" placeholder="Filter..." size="md" type="search"
                                variant="outline" />

                        </div>
                        <div class="pt-4 max-h-[70svh] overflow-y-auto">
                            <table class="w-full  text-xs text-left rtl:text-right text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                                    <tr>
                                        <th class="px-6 py-3 " scope="col">
                                            <div class="flex items-center gap-2">
                                                Student Name
                                            </div>
                                        </th>
                                        <th class="px-6 py-3" scope="col">
                                            Remarks
                                        </th>


                                        <th class="px-6 py-3" scope="col">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="(student, index) in applicationData?.students" :key="student.id"
                                        class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
                                        <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                            scope="row">
                                            {{ student?.first_name + ' ' + student?.middle_name + ' ' +
                                            student?.last_name }}
                                   
                                            <li v-for="(spec, key) in student?.specialization" :key="spec">{{ spec }}
                                                <UIcon class="text-red-500 cursor-pointer" name="mdi:close"
                                                    @click="removeSpecialization(index, key)" />

                                            </li>
                                        </th>

                                        <td class="px-6 py-4">
                                            <li>
                                                dsadad
                                            </li>
                                            <li>ddddddddddd</li>
                                        </td>

                                        <td class="px-6 py-4">
                                            <UButton icon="material-symbols:check" variant="ghost" color="success" />
                                            <UButton icon="material-symbols:close" variant="ghost" />
                                            <UButton icon="mdi-light:comment" variant="ghost" color="info" />
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </section>
                </section>
                   
            </form>
        </template>
     

    </UModal>


</template>
<script lang="ts" setup>

import { useMutation } from '@tanstack/vue-query'
import { fetchLatestCurriculum } from "#shared/API/School/FirstTimeLoginApi";

import type { FirstTimeLoginType } from "#shared/types/School/firstTimeLoginType";
import { updateSchoolAccount, viewAttachment } from "#shared/API/Qad/SchoolAccountApi";

const route = useRoute();

const toast = useToast()
const queryClient = useQueryClient();
const schoolCredentails = reactive<FirstTimeLoginType>({
    program_offered: [],


})

const open = ref<boolean>(false)
const schoolPrograms = reactive<{ track: string, track_key: string, strand: string, specialization: string[] }>({
    track: '',
    track_key: '',
    strand: '',
    specialization: []
})
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const strandArr = ref<{ [key: string]: any; }[]>([]);
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const specializationArr = ref<{ [key: string]: any; }[]>([]);
watch(() => schoolPrograms.track, (newTrack) => {
    schoolPrograms.strand = '';
    schoolPrograms.specialization = [];
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const newData = data.value?.programs.find((value: any) => {
        return value.id === newTrack

    })
    strandArr.value = newData?.strand;
    schoolPrograms.track_key = newData?.track_key;
})
watch(() => schoolPrograms.strand, (newStrand) => {
    specializationArr.value = [];
    // schoolPrograms.strand = '';
    if (newStrand) {
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        const newData = strandArr.value.find((value: any) => {
            return value.name === newStrand

        })

        specializationArr.value = newData?.values ?? [];
    }
})
// eslint-disable-next-line vue/prop-name-casing, @typescript-eslint/no-explicit-any
const props = defineProps<{ school_id?: string | number, label?: string, applicationData: {[key:string]: any} }>()

const selectedPdf = ref<string>('');
const { mutate: viewAttachmentFunc, data: attachmentData } = useMutation({
    mutationFn: () => viewAttachment(selectedPdf.value),

})
watch(() => selectedPdf.value, () => {
    viewAttachmentFunc()
})


const removeSpecialization = (trackIndex: number, specIndex: number) => {
    schoolCredentails.program_offered[trackIndex].specialization.splice(specIndex, 1);
};

const { data } = useQuery({
    queryKey: ['FIRST_TIME_LOGIN'],
    queryFn: () => fetchLatestCurriculum()
})
const { mutate: updateSchoolAccountFunc, error, isPending } = useMutation({
    mutationFn: () => updateSchoolAccount(props.school_id || 0, schoolCredentails),
    onSuccess: (data) => {
        queryClient.invalidateQueries({ queryKey: ["QAD_SCHOOL_ACCOUNT"] });
        open.value = false;
        toast.add({
            title: data,
            color: 'success',
            icon: 'ooui:success'
        })
    },
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    onError: (_err: any) => {

    },
});
const cleanName = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const datax = props.applicationData?.documents?.filter((element:any) => {
    
        return element.document_name.endsWith('.pdf');
    });
    return datax;
//   return value.replace(/\.xlsx$/i, '')
//     .replace(/\.pdf$/i, '')
//     .replace(/\.docx$/i, '')
});
</script>