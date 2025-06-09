<template>

    <div>
        <UModal v-model:open="open" :dismissible="false" :modal="true" title="New Template">
            <template #body>
                <form  @submit.prevent="storeApplicationFunc()">
                    <main class=" space-y-1">
                      <UFormField
            :error="error?.type && error?.type[0]" label="Type"
            required>

          <USelectMenu
              v-model="type" :items="['Qad','School']" :multiple="false"
              class="w-full" size="lg" value-key="id"/>
        </UFormField>
                        <UFormField :error="error?.file && error?.file[0]" label="File" required>
                            <UInput accept=".pdf,.doc,.docx,.xls,.xlsx" class="w-full" size="lg"
                                @change="(e: Event) => (file = (e.target as HTMLInputElement).files?.[0] || null)"
                                type="file" variant="outline" />

                        </UFormField>

                        <div class="space-x-2 mt-7 flex justify-end">
                            <UButton color="neutral" label="Cancel" type="button" variant="outline"
                                @click="open = false" />
                            <UButton :disabled="isPending" :loading="isPending" color="secondary" type="submit">
                                Submit
                            </UButton>
                        </div>
                    </main>

                </form>
            </template>
            <UButton color="error" icon="basil:add-outline" label="New Template" size="md" type="button"
                variant="solid" />
        </UModal>

    </div>

</template>
<script lang="ts" setup>
import { useMutation } from '@tanstack/vue-query'

import { storeNewTemplate } from '~/shared/API/Qad/TemplateApi';

const open = ref<boolean>(false)
const toast = useToast()
const route = useRoute();
const queryClient = useQueryClient();
const file = ref<File | null>(null)
const type = ref<string>('')
const { mutate: storeApplicationFunc, error, isPending } = useMutation({
    mutationFn: () => storeNewTemplate(file.value,type.value),
    onSuccess: (data) => {
        queryClient.invalidateQueries({ queryKey: ['QAD_TEMPLATE'] })
        open.value = false;
        file.value = null;
 type.value = '';
        toast.add({
            title: data,
            color: 'success',
            icon: 'ooui:success'
        })
    },
    onError: (error: any) => {

    },
});

</script>