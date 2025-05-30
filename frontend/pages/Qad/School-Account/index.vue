<template>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <UCard >
      <template #header>
        <div class="flex justify-between items-center gap-2">
          <QadNewSchool/>
          <QadNewBulkSchool/>
          <UInput v-model="search" type="search" icon="i-lucide-search" placeholder="Search..." size="md" variant="outline"/>

        </div>
      </template>

      <div class="overflow-x-auto">
        <table class="w-full  text-sm text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
          <tr>
            <th class="px-6 py-3 " scope="col">

              <div class="flex items-center gap-2">
                School Name <UIcon name="lsicon:sort-filled" size="1rem" class="cursor-pointer hover:text-black" @click="sortColumn('school_name')"/>
              </div>
            </th>
            <th class="px-6 py-3" scope="col">
              <div class="flex items-center gap-2">
                School Number  <UIcon name="lsicon:sort-filled" size="1rem" class="cursor-pointer hover:text-black" @click="sortColumn('school_number')"/>
              </div>
            </th>
            <th class="px-6 py-3" scope="col">
              School Division Office
            </th>
            <th class="px-6 py-3" scope="col">
              School Address
            </th>
            <th class="px-6 py-3" scope="col">
              School Admin Name
            </th>
            <th class="px-6 py-3" scope="col">
              School Email
            </th>
            <th class="px-6 py-3" scope="col">
              School Contact No.
            </th>
            <th class="px-6 py-3" scope="col">
              Account Status
            </th>
            <th class="px-6 py-3" scope="col">
              Action
            </th>
          </tr>
          </thead>
          <tbody>
          <tr v-show="isLoading">
            <td colspan="8" class=" w-full">
              <div class="flex justify-center py-10">
                <UIcon name="ant-design:loading-3-quarters-outlined"  class="animate-spin text-black/80" size="3rem"/>
              </div>
            </td>
          </tr>
          <tr v-show="!isLoading && data?.data.length === 0">
            <td colspan="8" class=" w-full">
              <div class="flex justify-center py-10">
                <p class="text-red-500 font-bold">No Data Found</p>
              </div>
            </td>
          </tr>
          <tr
            v-for="schoolAccount in data?.data"  v-show="!isLoading" :key="schoolAccount.id"
              class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
            <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" scope="row">
              {{ schoolAccount?.school_name }}
            </th>
            <td class="px-6 py-4">
              {{ schoolAccount?.school_number }}
            </td>
            <td class="px-6 py-4">
              {{ schoolAccount?.sdo_information?.sdo_name }}
            </td>
            <td class="px-6 py-4">
              {{ schoolAccount?.school_address }}
            </td>
            <td class="px-6 py-4">
              {{ schoolAccount?.admin_first_name }} {{ schoolAccount?.admin_last_name}}
            </td>
            <td class="px-6 py-4 capitalize">
              {{ schoolAccount?.admin_contact_number }}
            </td>
            <td class="px-6 py-4 capitalize">
              {{ schoolAccount?.type }}
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center">
                <div
:class="schoolAccount?.status === 'approved' ? ' bg-green-500' : schoolAccount.status === 'pending' ?  'bg-orange-400' : ' bg-red-500'"
                     class="h-2.5 w-2.5 rounded-full me-2 "/>
                <span class="capitalize">{{ schoolAccount?.status }}</span>
              </div>

            </td>
            <td class="px-6 py-4">
              <QadEditSdo :sdo_account_id="schoolAccount.id"/>
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
import QadEditSdo from "~/components/Qad/SdoAccount/QadEditSdo.vue";
import debounce from 'lodash.debounce'
import QadNewSchool from "~/components/Qad/SchoolAccount/QadNewSchool.vue";
import {fetchSchoolAccount} from "#shared/API/Qad/SchoolAccountApi";
import QadNewBulkSchool from "~/components/Qad/SchoolAccount/QadNewBulkSchool.vue";

const route = useRoute();
const queryClient = useQueryClient();
const page = ref<number>(parseInt(route?.query?.page as string) || 1);
const search = ref<string>('');
const sort = ref<string>('school_name')
const direction = ref<string>('asc')
const {data,isLoading} = useQuery({
  queryKey: ['QAD_SCHOOL_ACCOUNT', page,direction],
  queryFn: () => fetchSchoolAccount(page, search.value,sort,direction),

})
watch(() => search.value, debounce(() => {
  queryClient.invalidateQueries({queryKey: ["QAD_SCHOOL_ACCOUNT"]});
}, 300))
watch(() => page.value, (page) => {
  navigateTo({name: 'Qad-School-Account', query: {page: page}})
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