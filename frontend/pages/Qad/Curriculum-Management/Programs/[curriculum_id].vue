<template>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <UCard >
      <template #header>
        <div class="flex justify-between items-center gap-2">
          <QadNewProgram/>
          <UInput v-model="search" type="search" icon="i-lucide-search" placeholder="Search..." size="md" variant="outline"/>

        </div>
      </template>

      <div class="overflow-x-auto">
        <table class="w-full  text-sm text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
          <tr>
            <th class="px-6 py-3 " scope="col">

              <div class="flex items-center gap-2">
                Track <UIcon name="lsicon:sort-filled" size="1rem" class="cursor-pointer hover:text-black"  @click="sortColumn('school_year_end')"/>
              </div>
            </th>
            <th class="px-6 py-3" scope="col">
              Strand
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
              v-for="program in data?.data"  v-show="!isLoading" :key="program.id"
              class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
            <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" scope="row">
              {{ program?.track }}
            </th>
            <td class="px-6 py-4">
              {{  }}
              <div v-for="(track,index) in program.strand" :key="track.id">
                 <strong>{{index}}:</strong>
               <div>
                 <li v-for="spec in track" :key="spec">{{spec}}</li>
               </div>
              </div>
            </td>

            <td class="px-6 py-4">
              {{ program?.open_date }}
            </td>
            <td class="px-6 py-4">
              {{ program?.closing_date }}
            </td>

            <td class="px-6 py-4">
              <div class="flex items-center">
                <div
                    :class="program?.is_open_for_so_application === 'Open' ? ' bg-green-500' : ' bg-red-500'"
                    class="h-2.5 w-2.5 rounded-full me-2 "/>
                <span class="capitalize">{{ program?.is_open_for_so_application }}</span>
              </div>

            </td>
            <td class="px-6 py-4 space-x-2">


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
import {fetchCurricula, fetchPrograms} from "#shared/API/Qad/CurriculumManagementApi";
import QadEditCurriculum from "~/components/Qad/CurriculumManagement/QadEditCurriculum.vue";
import QadNewProgram from "~/components/Qad/CurriculumManagement/QadNewProgram.vue";


const route = useRoute();
const queryClient = useQueryClient();
const page = ref<number>(parseInt(route?.query?.page as string) || 1);
const search = ref<string>('');
const sort = ref<string>('')
const direction = ref<string>('asc')
const {data,isLoading} = useQuery({
  queryKey: ['QAD_CURRICULUM_MANAGEMENT', page,direction],
  queryFn: () => fetchPrograms(page, search.value,sort,direction,route.params.curriculum_id || 0),
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