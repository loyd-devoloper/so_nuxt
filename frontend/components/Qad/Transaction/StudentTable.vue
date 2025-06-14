<template>

  <div class="relative overflow-x-auto shadow-md  sm:rounded-lg">
    <UCard class="shadow-none border border-muted drop-shadow-none">
          <template #header  >
        <div class="flex justify-between items-center gap-2">


            <UInput
v-model="search" icon="i-lucide-search" placeholder="Filter..." size="md" type="search"
                    variant="outline"/>
    

        </div>
      </template>


      <div class="overflow-x-auto">
        <table class="w-full  text-sm text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
          <tr>
            <th class="px-6 py-3 " scope="col">

              <div class="flex items-center gap-2">
                LRN
                <UIcon
class="cursor-pointer hover:text-black" name="lsicon:sort-filled" size="1rem"
                       @click="sortColumn('lrn')"/>
              </div>
            </th>
            <th class="px-6 py-3" scope="col">
              <div class="flex items-center gap-2">
                First Name
                <UIcon
class="cursor-pointer hover:text-black" name="lsicon:sort-filled" size="1rem"
                       @click="sortColumn('first_name')"/>
              </div>
            </th>
            <th class="px-6 py-3" scope="col">
              Middle NAME
            </th>
               <th class="px-6 py-3" scope="col">
            <div class="flex items-center gap-2">
                Last Name
                <UIcon
class="cursor-pointer hover:text-black" name="lsicon:sort-filled" size="1rem"
                       @click="sortColumn('last_name')"/>
              </div>
            </th>
                    
               <th class="px-6 py-3" scope="col">
              Suffix
            </th>
            <th class="px-6 py-3" scope="col">
              Status
            </th>
 
          </tr>
          </thead>
          <tbody>
          <tr v-show="isLoading">
            <td class=" w-full" colspan="8">
              <div class="flex justify-center py-10">
                <UIcon class="animate-spin text-black/80" name="ant-design:loading-3-quarters-outlined" size="3rem"/>
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
          <tr
              v-for="student in data?.data" v-show="!isLoading" :key="student.id"
              class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
            <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" scope="row">
              {{ student.lrn }}
            </th>
            <td class="px-6 py-4">
              {{ student?.first_name }}
            </td>
            <td class="px-6 py-4">
              {{ student?.middle_name }}
            </td>
            <td class="px-6 py-4">
            {{ student?.last_name }}
            </td>
              <td class="px-6 py-4">
            {{ student?.suffix }}
            </td>
               <td class="px-6 py-4">
              <div class="flex items-center">
                <div
             
                    :class="{
                      'bg-green-500': student?.status === 'approved',
                      'bg-amber-500': student?.status === 'pending',
                      'bg-blue-500': student?.status === 'onprocess',
                              'bg-purple-500': student?.status === 'releasing',
                      'bg-cyan-500': student?.status === 'for_claim',
                      'bg-emerald-500': student?.status === 'released',
                    }"
                    class="h-2.5 w-2.5 rounded-full me-2 "/>
                <span class="capitalize">{{ student?.status }}</span>
              </div>

            </td>
   
         

          </tr>

         </tbody>
        </table>
      </div>

     <UPagination
         v-model:page="page" :items-per-page="data?.per_page" :sibling-count="0" :total="data?.total"
         class="pt-10  w-fit mx-auto" show-edges />

    </UCard>

  </div>
</template>
<script setup lang="ts">
import debounce from 'lodash.debounce'

import {  fetchSoStudents } from '~/shared/API/School/TransactionApi';

definePageMeta({
  middleware: ['school-middleware'],
  layout: 'school',
});


const route = useRoute();
const queryClient = useQueryClient();
const page = ref<number>(parseInt(route?.query?.page as string) || 1);
const search = ref<string>('');
const sort = ref<string>('last_name')
const direction = ref<string>('asc')
const {data,isLoading} = useQuery({
  queryKey: ['QAD_TRANSACTION_STUDENTS', page,direction,route?.params?.application_id],
  queryFn: () => fetchSoStudents(page, search.value,sort,direction,route?.params?.application_id),
})
watch(() => search.value, debounce(() => {
  queryClient.invalidateQueries({queryKey: ["QAD_TRANSACTION_STUDENTS"]});
}, 300))
watch(() => page.value, (page) => {
  navigateTo({name: 'Qad-Transaction-Students-application_id',params: {application_id:route?.params?.application_id}, query: {page: page}})
})
const sortColumn = (column:string) =>{
  direction.value = direction.value === 'asc' ? 'desc' : 'asc';
  sort.value = column
}
</script>