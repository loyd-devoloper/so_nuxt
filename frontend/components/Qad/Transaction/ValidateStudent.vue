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
                            <USelectMenu v-model="selectedPdf" :items="schoolAccountData?.account_attachments"
                                class="w-full" label-key="document_name" value-key="id" />
                        </UFormField>
                        <iframe :src="attachmentData" class="w-full h-[60svh] mt-5" />
                    </main>
                    <section>

                        <div class="flex justify-between items-center gap-2"> 
                            <UButton>APPROVED</UButton>

            <UInput icon="i-lucide-search" placeholder="Filter..." size="md" type="search"
                    variant="outline"/>
    
                        </div>
                        <div class="pt-4 max-h-[70svh] overflow-y-auto">
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

                                    <tr v-for="(track, index) in schoolCredentails.program_offered" :key="track.id"
                                        class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
                                        <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                            scope="row">
                                            {{ track?.track }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ track?.strand }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <li v-for="(spec, key) in track?.specialization" :key="spec">{{ spec }}
                                                <UIcon class="text-red-500 cursor-pointer" name="mdi:close"
                                                    @click="removeSpecialization(index, key)" />

                                            </li>
                                        </td>


                                        <td class="px-6 py-4">
                                            <UButton icon="iconamoon:trash-light" variant="ghost"
                                                @click="removeTrack(index)" />

                                            <!--                       <UIcon class="text-red-500 cursor-pointer" name="iconamoon:trash-light"-->
                                            <!--                                                 @click="removeSpecialization(index,key)"/>-->
                                        </td>
                                    </tr>
                                    <tr v-for="(track, index) in schoolCredentails.program_offered" :key="track.id"
                                        class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
                                        <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                            scope="row">
                                            {{ track?.track }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ track?.strand }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <li v-for="(spec, key) in track?.specialization" :key="spec">{{ spec }}
                                                <UIcon class="text-red-500 cursor-pointer" name="mdi:close"
                                                    @click="removeSpecialization(index, key)" />

                                            </li>
                                        </td>


                                        <td class="px-6 py-4">
                                            <UButton icon="iconamoon:trash-light" variant="ghost"
                                                @click="removeTrack(index)" />

                                            <!--                       <UIcon class="text-red-500 cursor-pointer" name="iconamoon:trash-light"-->
                                            <!--                                                 @click="removeSpecialization(index,key)"/>-->
                                        </td>
                                    </tr>
                                    <tr v-for="(track, index) in schoolCredentails.program_offered" :key="track.id"
                                        class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
                                        <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                            scope="row">
                                            {{ track?.track }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ track?.strand }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <li v-for="(spec, key) in track?.specialization" :key="spec">{{ spec }}
                                                <UIcon class="text-red-500 cursor-pointer" name="mdi:close"
                                                    @click="removeSpecialization(index, key)" />

                                            </li>
                                        </td>


                                        <td class="px-6 py-4">
                                            <UButton icon="iconamoon:trash-light" variant="ghost"
                                                @click="removeTrack(index)" />

                                            <!--                       <UIcon class="text-red-500 cursor-pointer" name="iconamoon:trash-light"-->
                                            <!--                                                 @click="removeSpecialization(index,key)"/>-->
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
import type { StepperItem } from '@nuxt/ui'
import type { FirstTimeLoginType } from "#shared/types/School/firstTimeLoginType";
import { editSchoolAccount, updateSchoolAccount, viewAttachment } from "#shared/API/Qad/SchoolAccountApi";


const toast = useToast()
const queryClient = useQueryClient();
const schoolCredentails = reactive<FirstTimeLoginType>({
    program_offered: [],


})
const show = ref(false)
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
// eslint-disable-next-line vue/prop-name-casing
const props = defineProps<{ school_id?: string | number, label?: string }>()
const { mutate, data: schoolAccountData } = useMutation({
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
const { mutate: viewAttachmentFunc, data: attachmentData } = useMutation({
    mutationFn: () => viewAttachment(selectedPdf.value),

})
watch(() => selectedPdf.value, () => {
    viewAttachmentFunc()
})
watch(() => open.value, (newOpen) => {
    if (newOpen) mutate()
})
const submitProgram = () => {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const checkIfExist = schoolCredentails.program_offered.find((track: any) => {
        return track.track === schoolPrograms.track_key && track.strand === schoolPrograms.strand;
    });
    if (checkIfExist === undefined) {
        schoolCredentails.program_offered.push({
            track: schoolPrograms.track_key,
            strand: schoolPrograms.strand,
            specialization: schoolPrograms.specialization,
        })
    } else {
        schoolPrograms.specialization.forEach((element: string) => {
            if (!checkIfExist.specialization.includes(element)) {
                checkIfExist.specialization.push(element);
            }
        });

    }

}
const removeTrack = (index: number) => {
    schoolCredentails.program_offered.splice(index, 1);

}
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

</script>