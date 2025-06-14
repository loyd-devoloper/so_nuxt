<template>
    <UModal v-model:open="open" :close="true" :dismissible="false" :ui="{

        content: 'max-w-screen-2xl'
    }" title="Validate So Application">
        <UButton color="success" icon="lucide:edit" :label="label ?? 'Validate'" size="sm" type="button"
            variant="outline" />
        <template #body>

            <form class="max-w-screen-2xl">
                <section class="grid grid-cols-2 gap-10">
                    <main>
                        <UFormField label="Select Document">
                            <USelectMenu v-model="selectedPdf" :items="cleanName" class="w-full"
                                label-key="document_name" value-key="id" />
                        </UFormField>
                        <iframe :src="attachmentData" class="w-full h-[60svh] mt-5" />
                    </main>
                    <section>

                        <div class="flex justify-between items-center gap-2">
                            <UButton
                                v-if="!isAllStudentApproved && authStore.authUser.id === applicationData.form_checker && checkIfButtonShow('is_form_checked')"
                                @click="changeValidatorStatusFunc('is_form_checked') ">
                                Approved as Form 9 Checker</UButton>
                            <UButton
                                v-if="!isAllStudentApproved && authStore.authUser.id === applicationData.evaluation_checker && checkIfButtonShow('is_evaluation_checked')"
                                @click="changeValidatorStatusFunc('is_evaluation_checked')">
                               Approved as Evaluator</UButton>
                                <UButton
                                v-if="!isAllStudentApproved && authStore.authUser.id === applicationData.review_checker && checkIfButtonShow('is_review_checked')"
                                @click="changeValidatorStatusFunc('is_review_checked')">
                               Approved as Reviewer</UButton>
                                <UButton
                                v-if="!isAllStudentApproved && authStore.authUser.id === applicationData.approve_checker && checkIfButtonShow('is_approve_checked')"
                                @click="approveApplicationFunc()">
                               Approved & Generate So</UButton>
                            <UInput icon="i-lucide-search" v-model="search" placeholder="Filter..." size="md"
                                type="search" variant="outline" />

                        </div>
                        <div class="flex gap-2 text-xs pt-5">
                            <p class="flex items-center">
                                <UIcon name="icon-park-outline:dot" class="text-secondary" /> ALL ({{
                                applicationData?.students_count }})
                            </p>
                            <p class="flex items-center">
                                <UIcon name="icon-park-outline:dot" class="text-success" /> APPROVED ({{
                                applicationData?.students_approved }})
                            </p>
                            <p class="flex items-center">
                                <UIcon name="icon-park-outline:dot" class="text-error" /> REJECTED ({{
                                applicationData?.students_rejected }})
                            </p>


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
                                            :class="{
                                                'bg-green-500': student.status === 'approved',
                                                'bg-red-500 text-white': student.status === 'rejected',
                                            }" scope="row">
                                            {{ student?.first_name + ' ' + student?.middle_name + ' ' +
                                                student?.last_name }}

                                            <li v-for="(spec, key) in student?.specialization" :key="spec">{{ spec }}
                                                <UIcon class="text-red-500 cursor-pointer" name="mdi:close" />

                                            </li>
                                        </th>

                                        <td class="px-6 py-4">

                                            <li>
                                                dsadad
                                            </li>
                                            <li>ddddddddddd</li>
                                        </td>

                                        <td class="px-6 py-4" v-if="applicationData.status === 'onprocess'">
                                            <UButton @click="mutate({ student_id: student.id, type: 'approved' })"
                                                icon="material-symbols:check" variant="ghost" color="success" />
                                            <UButton @click="mutate({ student_id: student.id, type: 'rejected' })"
                                                icon="material-symbols:close" variant="ghost" />
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
import {  viewAttachment } from "#shared/API/Qad/SchoolAccountApi";
import { debounce } from 'lodash';
import { approveApplication, changeStudentStatus, updateValidatorStatus } from '~/shared/API/Qad/TransactionApi';
const authStore = useAuthStore();
const route = useRoute();

const toast = useToast()
const queryClient = useQueryClient();

const open = ref<boolean>(false)

// eslint-disable-next-line vue/prop-name-casing, @typescript-eslint/no-explicit-any
const props = defineProps<{ school_id?: string | number, label?: string, applicationData: { [key: string]: any }, searchValue: string }>()

const selectedPdf = ref<string>('');
const { mutate: viewAttachmentFunc, data: attachmentData } = useMutation({
    mutationFn: () => viewAttachment(selectedPdf.value),

})
watch(() => selectedPdf.value, () => {
    viewAttachmentFunc()
})
const { mutate } = useMutation({
    mutationFn: (data: { student_id: string, type: string }) => changeStudentStatus(data),
    onSettled: () => {
        queryClient.invalidateQueries({ queryKey: ['QAD_TRANSACTION_APPLICATION'] })
        queryClient.invalidateQueries({ queryKey: ['QAD_TRANSACTION_STUDENTS'] })

    }
})

const { mutate: changeValidatorStatusFunc } = useMutation({
    mutationFn: (type: string) => updateValidatorStatus(route?.params?.application_id || '', type),
   onSettled:(data) =>{
       queryClient.invalidateQueries({ queryKey: ['QAD_TRANSACTION_APPLICATION'] })
    toast.add({
      title: data,
      color: 'success',
      icon:'ooui:success'
    })
   }
   
})
const { mutate: approveApplicationFunc } = useMutation({
    mutationFn: () => approveApplication(route?.params?.application_id || ''),
   onSettled:(data) =>{
       queryClient.invalidateQueries({ queryKey: ['QAD_TRANSACTION_APPLICATION'] })
    toast.add({
      title: data,
      color: 'success',
      icon:'ooui:success'
    })
   }
   
})
const isAllStudentApproved = computed(() => {
    console.log(props.applicationData.students.find((student: { [key: string]: any }) => student.status === 'pending' || student.status === 'rejected') )
    return props.applicationData.students.find((student: { [key: string]: any }) => student.status === 'pending' || student.status === 'rejected') ? true : false;
});


const search = ref<string>('')
// Debounced watcher
watch(
    search,
    debounce((newValue: string) => {
        queryClient.invalidateQueries({ queryKey: ['QAD_TRANSACTION_APPLICATION'] })
    }, 300),
    { immediate: true }
);
const checkIfButtonShow = (type:string) =>{
      if(type === 'is_form_checked') return !props.applicationData?.is_form_checked && !props.applicationData?.is_evaluation_checked && !props.applicationData?.is_review_checked && !props.applicationData?.is_approve_checked;
   if(type === 'is_evaluation_checked' ) return  props.applicationData?.is_form_checked && !props.applicationData?.is_evaluation_checked && !props.applicationData?.is_review_checked && !props.applicationData?.is_approve_checked;
    if(type === 'is_review_checked' ) return  props.applicationData?.is_form_checked && props.applicationData?.is_evaluation_checked && !props.applicationData?.is_review_checked && !props.applicationData?.is_approve_checked;
     if(type === 'is_approve_checked' ) return props.applicationData?.is_form_checked && props.applicationData?.is_evaluation_checked && props.applicationData?.is_review_checked && !props.applicationData?.is_approve_checked;
}

const cleanName = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const datax = props.applicationData?.documents?.filter((element: any) => {

        return element.document_name.endsWith('.pdf');
    });
    return datax;

});
</script>