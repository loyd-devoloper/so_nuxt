<template>

    <div>
        <UModal v-model:open="open" :dismissible="false" :modal="true" title="SPECIAL ORDER" :ui="{
            content:'max-w-screen-2xl '
        }">
            <template #body>
                 <iframe :src="data" class="w-full h-[70svh]"></iframe>
            </template>
            <slot/>
        </UModal>

    </div>

</template>
<script lang="ts" setup>
import { useMutation } from '@tanstack/vue-query'
import { previewAttachment } from '~/shared/API/Qad/AnnouncementApi';
import { generateSo } from '~/shared/API/Qad/TransactionApi';
import type { AnnouncementType } from '~/shared/types/Qad/AnnouncementType';

const open = ref<boolean>(false)
const toast = useToast()
const route = useRoute();
const queryClient = useQueryClient();
const announcementData = reactive<AnnouncementType>({})
const props = defineProps<{application_id?:any}>()
const {mutate, data} = useMutation({
  mutationFn:() => generateSo(props.application_id),

})
watch(() => open.value,(newOpen) =>{
  if(newOpen) mutate()
})


</script>