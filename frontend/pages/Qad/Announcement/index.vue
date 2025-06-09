<template>
    <div>

        <UCard class="shadow-none border border-black/10 drop-shadow-none">
            <template #header class="pb-4">
                <div class="flex justify-between items-center gap-2">


                    <UInput v-model="search" icon="i-lucide-search" placeholder="Filter..." size="md" type="search"
                        variant="outline" />
                    <QadNewAnnouncement />


                </div>
            </template>

            <div class="overflow-x-auto">
                <table class="w-full  text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                        <tr>

                            <th class="px-6 py-3 " scope="col">

                                <div class="flex items-center gap-2">
                                    Type
                                    <UIcon class="cursor-pointer hover:text-black" name="lsicon:sort-filled" size="1rem"
                                        @click="sortColumn('type')" />
                                </div>
                            </th>
                            <th class="px-6 py-3" scope="col">
                                Content
                            </th>
                            <th class="px-6 py-3" scope="col">
                                Expiration Date
                            </th>
                            <th class="px-6 py-3" scope="col">
                                Create By
                            </th>

                            <th class="px-6 py-3" scope="col">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-show="isLoading">
                            <td class=" w-full" colspan="8">
                                <div class="flex justify-center py-10">
                                    <UIcon class="animate-spin text-black/80"
                                        name="ant-design:loading-3-quarters-outlined" size="3rem" />
                                </div>
                            </td>
                        </tr>
                        <tr v-show="!isLoading && data?.data.length === 0">
                            <td class=" w-full" colspan="8">
                                <div class="flex justify-center py-10">
                                    <p class="text-red-500 font-bold">No Data Found</p>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="announcement in data?.data" v-show="!isLoading" :key="announcement.id"
                            class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
                            <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                scope="row">
                                <UBadge variant="subtle" color="info">{{ announcement?.type }}</UBadge>
                            </th>
                            <td class="px-6 py-4 prose " >
                                <QadAnnouncementQadPreviewAnnouncement v-if="announcement.file" :announcement_id="announcement.id">PREVIEW</QadAnnouncementQadPreviewAnnouncement>
                            </td>
                            <td class="px-6 py-4">
                                {{ announcement?.expiration_date }}
                            </td>

                            <td class="px-6 py-4">
                                {{ announcement?.user_info.fname + ' ' + announcement?.user_info?.lname }}
                            </td>



                            <td class="px-6 py-4 flex items-center gap-2">
                                <QadAnnouncementQadEditAnnouncement :announcement_id="announcement.id" />
                                <UButton label="Delete" size="sm" icon="iconamoon:trash" color="error" variant="outline"
                                    @click="mutate(announcement.id)" />
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>


            <UPagination v-model:page="page" :items-per-page="data?.per_page" :sibling-count="1" :total="data?.total"
                class="pt-10  w-fit mx-auto " show-edges />

        </UCard>



    </div>
</template>
<script setup lang="ts">
definePageMeta({
    middleware: ['qad-middleware'],
    layout: 'adminlayout',
});
import { QadAnnouncementQadPreviewAnnouncement } from '#components';
import debounce from 'lodash.debounce'
import QadNewAnnouncement from '~/components/Qad/Announcement/QadNewAnnouncement.vue';
import { fetchAnnouncement } from '~/shared/API/Qad/AnnouncementApi';
import { deleteTemplate } from '~/shared/API/Qad/TemplateApi';

const route = useRoute();
const queryClient = useQueryClient();
const page = ref<number>(parseInt(route?.query?.page as string) || 1);
const search = ref<string>('');
const sort = ref<string>('')
const direction = ref<string>('asc')
const { data, isLoading } = useQuery({
    queryKey: ['QAD_ANNOUNCEMENT', page, direction],
    queryFn: () => fetchAnnouncement(page, search.value, sort, direction),
})
const { mutate } = useMutation({
    mutationFn: (id: string) => deleteTemplate(id),
    onSettled: () => {
        queryClient.invalidateQueries({ queryKey: ["QAD_ANNOUNCEMENT"] });
    }
})
watch(() => search.value, debounce(() => {
    queryClient.invalidateQueries({ queryKey: ["QAD_ANNOUNCEMENT"] });
}, 300))
watch(() => page.value, (page) => {
    navigateTo({ name: 'Qad-Template', query: { page: page } })
})
const sortColumn = (column: string) => {
    direction.value = direction.value === 'asc' ? 'desc' : 'asc';
    sort.value = column
}
const cleanName = (value: any) => {
    return value.replace(/\.xlsx$/i, '')
        .replace(/\.pdf$/i, '')
        .replace(/\.docx$/i, '')
};
</script>