<template>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <UCard >
      <template #header>
        <div class="flex justify-between items-center gap-2">
         
          <UInput v-model="search" type="search" icon="i-lucide-search" placeholder="Search..." size="md" variant="outline"/>
 <QadNewSdo/>
        </div>
      </template>

     <div class="overflow-x-auto">
       <table class="w-full  text-xs text-left rtl:text-right text-gray-500">
         <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
         <tr>
           <th class="px-6 py-3 " scope="col">

             <div class="flex items-center gap-2">
               SDO Name <UIcon name="lsicon:sort-filled" size="1rem" class="cursor-pointer hover:text-black" @click="sortColumn('sdo_name')"/>
             </div>
           </th>
           <th class="px-6 py-3" scope="col">
             <div class="flex items-center gap-2">
               SDO Code  <UIcon name="lsicon:sort-filled" size="1rem" class="cursor-pointer hover:text-black" @click="sortColumn('sdo_code')"/>
             </div>
           </th>
           <th class="px-6 py-3" scope="col">
             SDS Name
           </th>
           <th class="px-6 py-3" scope="col">
             ASDS Name
           </th>
           <th class="px-6 py-3" scope="col">
             Type
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
v-for="sdoAccount in data?.data"  v-show="!isLoading" :key="sdoAccount.id"
             class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
           <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" scope="row">
             {{ sdoAccount?.sdo_name }}
           </th>
           <td class="px-6 py-4">
             {{ sdoAccount?.sdo_code }}
           </td>
           <td class="px-6 py-4">
             {{ sdoAccount?.sds_name }}
           </td>
           <td class="px-6 py-4">
             {{ sdoAccount?.asds_name }}
           </td>
           <td class="px-6 py-4 capitalize">
             {{ sdoAccount?.type }}
           </td>
           <td class="px-6 py-4">
             
              <UBadge :color="sdoAccount?.status === 'active' ? 'success' : 'error'" variant="subtle" class="capitalize font-bold">{{ sdoAccount?.status }}</UBadge>
           </td>
           <td class="px-6 py-4">
             <QadEditSdo :sdo_account_id="sdoAccount.id"/>
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

import QadNewSdo from "~/components/Qad/SdoAccount/QadNewSdo.vue";
import {fetchSdoAccount} from "#shared/API/Qad/SdoAccountApi";
import QadEditSdo from "~/components/Qad/SdoAccount/QadEditSdo.vue";
import debounce from 'lodash.debounce'

const route = useRoute();
const queryClient = useQueryClient();
const page = ref<number>(parseInt(route?.query?.page as string) || 1);
const search = ref<string>('');
const sort = ref<string>('sdo_name')
const direction = ref<string>('asc')
const {data,isLoading} = useQuery({
  queryKey: ['QAD_SDO_ACCOUNT', page,direction],
  queryFn: () => fetchSdoAccount(page, search.value,sort,direction),

})
watch(() => search.value, debounce(() => {
  queryClient.invalidateQueries({queryKey: ["QAD_SDO_ACCOUNT"]});
}, 300))
watch(() => page.value, (page) => {
  navigateTo({name: 'Qad-SDO-Account', query: {page: page}})
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