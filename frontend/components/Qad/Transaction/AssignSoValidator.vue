<template>


    <UModal :open="open" :close="{onClick: () => closeModal()}" title="New School Account">
        <template #title>
            Assign Validator
        </template>
       
        <!-- <UButton label="Assign Validator" type="button" variant="ghost" color="neutral" icon="iwwa:assign" size="sm" /> -->
        <template #body>
            <form class=" space-y-3" @submit.prevent="assignValidatorFunc()">
                
                <UFormField :error="error?.form_checker && error?.form_checker[0]" label="Form 9 Checker" required>

                    <USelectMenu v-model="qadAccountData.form_checker"  :filter-fields="['fname','lname']" :items="qadAccounts" label-key="fname" value-key="id"
                        :multiple="false" class="w-full" size="lg" >
                        <template #item-label="{item}">
                            {{ item.fname +' '+ item.lname }}
                        </template>
                       
                    </USelectMenu>
                </UFormField>
                   <UFormField :error="error?.evaluation_checker && error?.evaluation_checker[0]" label="Evaluator" required>

                    <USelectMenu v-model="qadAccountData.evaluation_checker"  :filter-fields="['fname','lname']" :items="qadAccounts" label-key="fname" value-key="id"
                        :multiple="false" class="w-full" size="lg" >
                        <template #item-label="{item}">
                            {{ item.fname +' '+ item.lname }}
                        </template>
                       
                    </USelectMenu>
                </UFormField>
                   <UFormField :error="error?.review_checker && error?.review_checker[0]" label="Reviewer" required>

                    <USelectMenu v-model="qadAccountData.review_checker"  :filter-fields="['fname','lname']" :items="qadAccounts" label-key="fname" value-key="id"
                        :multiple="false" class="w-full" size="lg" >
                        <template #item-label="{item}">
                            {{ item.fname +' '+ item.lname }}
                        </template>
                       
                    </USelectMenu>
                </UFormField>
                   <UFormField :error="error?.approve_checker && error?.approve_checker[0]" label="Approver" required>

                    <USelectMenu v-model="qadAccountData.approve_checker"  :filter-fields="['fname','lname']" :items="qadAccounts" label-key="fname" value-key="id"
                        :multiple="false" class="w-full" size="lg" >
                        <template #item-label="{item}">
                            {{ item.fname +' '+ item.lname }}
                        </template>
                       
                    </USelectMenu>
                </UFormField>
               
                <div class="space-x-2 mt-7 flex justify-end">
                    <UButton color="neutral" label="Cancel" type="button" variant="outline" @click="closeModal" />
                    <UButton :disabled="isPending" :loading="isPending" color="success" type="submit">
                        Assign
                    </UButton>
                </div>
            </form>
        </template>


    </UModal>


</template>
<script lang="ts" setup>

import { useMutation } from '@tanstack/vue-query'
import { assignValidator, getQadAccounts } from '~/shared/API/Qad/TransactionApi';
import type { QadAccount } from '~/shared/types/Qad/TransactionType';
const props = defineProps<{ open: boolean,applicationData:{[key:string]: any}}>()
const toast = useToast()
const show = ref<boolean>(false)
// const open = ref<boolean>(false)
const queryClient = useQueryClient();
const qadAccountData = reactive<QadAccount>({
    approve_checker: props?.applicationData?.approve_checker,
    form_checker:props?.applicationData?.form_checker,
    evaluation_checker: props?.applicationData?.evaluation_checker,
    review_checker:  props?.applicationData?.review_checker
})

const {data:qadAccounts} = useQuery({
    queryKey:['QAD_TRANSACTION_QAD_ACCOUNT'],
    queryFn:() => getQadAccounts()
})
    const emit = defineEmits(['closeModal']);

const closeModal = () => {
    console.log('ss')
  emit('closeModal');
}

const { mutate: assignValidatorFunc, error, isPending } = useMutation({
    mutationFn: () => assignValidator(props.applicationData?.application_id || '',qadAccountData),
    onSuccess: (data) => {
        queryClient.invalidateQueries({ queryKey: ["QAD_TRANSACTION"] });
        
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