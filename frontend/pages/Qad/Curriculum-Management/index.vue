<template>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <UCard >
      <template #header>
        <div class="flex justify-between items-center gap-2">

          <UInput v-model="search" type="search" icon="i-lucide-search" placeholder="Search..." size="md" variant="outline"/>
          <QadCurriculumManagementQadNewCurriculum/>
        </div>
      </template>

      <div class="overflow-x-auto">
        <table class="w-full  text-sm text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
          <tr>
            <th class="px-6 py-3 " scope="col">

              <div class="flex items-center gap-2">
               Curriculum <UIcon name="lsicon:sort-filled" size="1rem" class="cursor-pointer hover:text-black"  @click="sortColumn('school_year_end')"/>
              </div>
            </th>
            <th class="px-6 py-3" scope="col">
              <div class="flex items-center gap-2">
                RD
              </div>
            </th>
            <th class="px-6 py-3" scope="col">
              ARD
            </th>
            <th class="px-6 py-3" scope="col">
              Open Date
            </th>
            <th class="px-6 py-3" scope="col">
              Closing Date
            </th>
            <th class="px-6 py-3" scope="col">
              Status
            </th>

            <th class="px-6 py-3" scope="col">
              Action
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-show="isLoading">
            <td colspan="7" class=" w-full">
              <div class="flex justify-center py-10">
                <UIcon name="ant-design:loading-3-quarters-outlined"  class="animate-spin text-black/80" size="3rem"/>
              </div>
            </td>
          </tr>
          <tr v-show="!isLoading && data?.data.length === 0">
            <td colspan="7" class=" w-full">
              <div class="flex justify-center py-10">
                <p class="text-red-500 font-bold">No Data Found</p>
              </div>
            </td>
          </tr>
          <tr
v-for="curriculum in data?.data"  v-show="!isLoading" :key="curriculum.id"
              class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
            <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" scope="row">
              {{ curriculum?.school_year_start+'-'+curriculum?.school_year_end }}
            </th>
            <td class="px-6 py-4">
              {{ curriculum?.regional_director }}
            </td>
            <td class="px-6 py-4">
              {{ curriculum?.assistant_regional_director }}
            </td>
            <td class="px-6 py-4">
              {{ curriculum?.open_date }}
            </td>
            <td class="px-6 py-4">
              {{ curriculum?.closing_date }}
            </td>

            <td class="px-6 py-4">
              <div class="flex items-center">
                <div
                    :class="curriculum?.is_open_for_so_application === 'Open' ? ' bg-green-500' : ' bg-red-500'"
                     class="h-2.5 w-2.5 rounded-full me-2 "/>
                <span class="capitalize">{{ curriculum?.is_open_for_so_application }}</span>
              </div>

            </td>
            <td class="px-6 py-4 space-x-2">
              <QadEditCurriculum :curriculum_id="curriculum.id"/>
              <UButton :to="{name:'Qad-Curriculum-Management-Programs-curriculum_id',params:{curriculum_id: curriculum.id}}" color="neutral" icon="material-symbols:list-alt-outline" size="sm"  type="button" variant="outline">Programs</UButton>

            </td>
          </tr>

          </tbody>
        </table>
      </div>

      <UPagination
                  v-model:page="page" :items-per-page="data?.per_page" :sibling-count="1" :total="data?.total"
                   class="pt-10  w-fit mx-auto" show-edges/>

    </UCard>

  </div>


</template>
<script lang="ts" setup>

import debounce from 'lodash.debounce'
import {fetchCurricula} from "#shared/API/Qad/CurriculumManagementApi";
const route = useRoute();
const queryClient = useQueryClient();
const page = ref<number>(parseInt(route?.query?.page as string) || 1);
const search = ref<string>('');
const sort = ref<string>('school_year_end')
const direction = ref<string>('asc')
const {data,isLoading} = useQuery({
  queryKey: ['QAD_CURRICULUM_MANAGEMENT', page,direction],
  queryFn: () => fetchCurricula(page, search.value,sort,direction),
})
watch(() => search.value, debounce(() => {
  queryClient.invalidateQueries({queryKey: ["QAD_CURRICULUM_MANAGEMENT"]});
}, 300))
watch(() => page.value, (page) => {
  navigateTo({name: 'Qad-Curriculum-Management', query: {page: page}})
})
const sortColumn = (column:string) =>{
  direction.value = direction.value === 'asc' ? 'desc' : 'asc';
  sort.value = column
}
definePageMeta({
  middleware: ['qad-middleware'],
  layout: 'adminlayout',
});
</script>