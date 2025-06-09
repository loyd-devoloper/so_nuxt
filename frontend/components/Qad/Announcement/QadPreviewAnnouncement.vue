<template>

    <div>
        <UModal v-model:open="open" :dismissible="false" :modal="true" title="Attachment" :ui="{
            content:'max-w-screen-2xl '
        }">
            <template #body>
                 <iframe :src="data" class="w-full h-[70svh]"></iframe>
            </template>
             <UButton color="warning" icon="fontisto:preview" label="preview" size="sm"  type="button" variant="solid"/>
        </UModal>

    </div>

</template>
<script lang="ts" setup>
import { useMutation } from '@tanstack/vue-query'
import { previewAttachment } from '~/shared/API/Qad/AnnouncementApi';
import type { AnnouncementType } from '~/shared/types/Qad/AnnouncementType';

const open = ref<boolean>(false)
const toast = useToast()
const route = useRoute();
const queryClient = useQueryClient();
const announcementData = reactive<AnnouncementType>({})
const props = defineProps<{announcement_id?:string | number}>()
const {mutate, data} = useMutation({
  mutationFn:() => previewAttachment(props?.announcement_id || 0),

})
watch(() => open.value,(newOpen) =>{
  if(newOpen) mutate()
})


</script>