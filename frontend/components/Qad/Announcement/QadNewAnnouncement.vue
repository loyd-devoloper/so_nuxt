<template>

    <div>
        <UModal v-model:open="open" :dismissible="false" :modal="true" title="New Announcement" :ui="{
            content:'max-w-2xl'
        }">
            <template #body>
                <form @submit.prevent="storeApplicationFunc()">
                    <main class=" space-y-2">
                        <UFormField :error="error?.type && error?.type[0]" label="Type" required>

                            <USelectMenu v-model="announcementData.type" :items="['Office Advisory', 'Regional Advisory','DepEd Advisory','Other Advisory']" :multiple="false" class="w-full"
                                size="lg" value-key="id" />
                        </UFormField>
                         <UFormField :error="error?.file && error?.file[0]" label="File" hint="Optional">
                            <UInput accept=".pdf,application/pdf" class="w-full" size="lg"
                                @change="(e: Event) => (announcementData.file = (e.target as HTMLInputElement).files?.[0] || null)"
                                type="file" variant="outline" />

                        </UFormField>
                        <UFormField :error="error?.expiration_date && error?.expiration_date[0]" label="Expiration Date" required>
                            <UInput v-model="announcementData.expiration_date" class="w-full" size="lg"
                                
                                type="date" variant="outline" />

                        </UFormField>
                            <UFormField :error="error?.content && error?.content[0]" label="Content" required>
                                     <QuillEditor v-model:content="announcementData.content" contentType="html" theme="snow" toolbar="essential"
                            style="height: 200px;" :class="error?.content  ? 'border border-red-500' : 'border border-black/20' " />

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
            <UButton color="error" icon="basil:add-outline" label="New Announcement" size="md" type="button"
                variant="solid" />
        </UModal>

    </div>

</template>
<script lang="ts" setup>
import { useMutation } from '@tanstack/vue-query'
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import { storeNewAnnouncement } from '~/shared/API/Qad/AnnouncementApi';
import type { AnnouncementType } from '~/shared/types/Qad/AnnouncementType';

const open = ref<boolean>(false)
const toast = useToast()
const route = useRoute();
const queryClient = useQueryClient();
const announcementData = reactive<AnnouncementType>({})
const { mutate: storeApplicationFunc, error, isPending } = useMutation({
    mutationFn: () => storeNewAnnouncement(announcementData),
    onSuccess: (data) => {
        queryClient.invalidateQueries({ queryKey: ['QAD_ANNOUNCEMENT'] })
        open.value = false;
        announcementData.file = null;
        announcementData.content = '';
        announcementData.expiration_date = '';
        announcementData.type = '';
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