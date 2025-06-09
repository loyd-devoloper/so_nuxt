<template>
    <div>

        <UCard class="shadow-none border border-black/10 drop-shadow-none">
            <template #header class="pb-4">
                <div class="flex justify-between items-center gap-2">


                    <UInput v-model="search" icon="i-lucide-search" placeholder="Filter..." size="md" type="search"
                        variant="outline" />
                    <QadNewTemplate />


                </div>
            </template>

            <div class="overflow-x-auto">
                <table class="w-full  text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                        <tr>

                            <th class="px-6 py-3 " scope="col">

                                <div class="flex items-center gap-2">
                                      Template Name
                                    <UIcon class="cursor-pointer hover:text-black" name="lsicon:sort-filled" size="1rem"
                                        @click="sortColumn('school_year_end')" />
                                </div>
                            </th>
                            <th class="px-6 py-3" scope="col">
                                Type
                            </th>
                            <th class="px-6 py-3" scope="col">
                                Uploader
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
                        <tr v-for="template in data?.data" v-show="!isLoading" :key="template.id"
                            class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
                            <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                scope="row">
                                {{template?.name
                                }}
                            </th>
                               <td class="px-6 py-4">
                                <UBadge variant="subtle" :color="template?.type =='Qad' ? 'primary' : 'info'">{{ template?.type }}</UBadge>
                            </td>
                               <td class="px-6 py-4">
                                {{ template?.user_info.fname +' '+template?.user_info?.lname }}
                            </td>
                
                        

                            <td class="px-6 py-4 space-x-2">
                                 <UButton label="Delete" size="sm" icon="iconamoon:trash" color="error"
                                        variant="outline" @click="mutate(template.id)"/>
                                          <UButton label="Download" size="sm" icon="material-symbols:download" color="info"
                                        variant="outline" @click="downloadTemplateFunc({id: template.id,filename: template.name})"/>
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
import debounce from 'lodash.debounce'
import QadNewTemplate from '~/components/Qad/Template/QadNewTemplate.vue';
import { deleteTemplate, downloadTemplate, fetchTemplate } from '~/shared/API/Qad/TemplateApi';

const route = useRoute();
const queryClient = useQueryClient();
const page = ref<number>(parseInt(route?.query?.page as string) || 1);
const search = ref<string>('');
const sort = ref<string>('')
const direction = ref<string>('asc')
const { data, isLoading } = useQuery({
    queryKey: ['QAD_TEMPLATE', page, direction],
    queryFn: () => fetchTemplate(page, search.value, sort, direction),
})
const {mutate} = useMutation({
    mutationFn: (id:string) =>deleteTemplate(id),
    onSettled:() =>{
         queryClient.invalidateQueries({ queryKey: ["QAD_TEMPLATE"] });
    }
})
const {mutate:downloadTemplateFunc} = useMutation({
    mutationFn: (data:{id:string,filename:string}) =>downloadTemplate(data.id,data.filename),
    onSettled:() =>{
         queryClient.invalidateQueries({ queryKey: ["QAD_TEMPLATE"] });
    }
})
watch(() => search.value, debounce(() => {
    queryClient.invalidateQueries({ queryKey: ["QAD_TEMPLATE"] });
}, 300))
watch(() => page.value, (page) => {
    navigateTo({ name: 'Qad-Template', query: { page: page } })
})
const sortColumn = (column: string) => {
    direction.value = direction.value === 'asc' ? 'desc' : 'asc';
    sort.value = column
}
const cleanName = (value :any) => {
  return value.replace(/\.xlsx$/i, '')
    .replace(/\.pdf$/i, '')
    .replace(/\.docx$/i, '')
};
</script>