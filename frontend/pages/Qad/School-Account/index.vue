<template>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <UCard>
      <template #header>
        <div class="flex justify-between items-center gap-2">

          <UInput
v-model="search" icon="i-lucide-search" placeholder="Search..." size="md" type="search"
                  variant="outline"/>
          <div class="space-x-1">
            <QadNewSchool/>
            <QadNewBulkSchool/>

          </div>
        </div>
      </template>

      <div class="overflow-x-auto">
        <table class="w-full  text-xs text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
          <tr>
            <th class="px-6 py-3 " scope="col">

              <div class="flex items-center gap-2">
                School Name
                <UIcon
class="cursor-pointer hover:text-black" name="lsicon:sort-filled" size="1rem"
                       @click="sortColumn('school_name')"/>
              </div>
            </th>
            <th class="px-6 py-3" scope="col">
              <div class="flex items-center gap-2">
                School Number
                <UIcon
class="cursor-pointer hover:text-black" name="lsicon:sort-filled" size="1rem"
                       @click="sortColumn('school_number')"/>
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
            <td class=" w-full" colspan="9">
              <div class="flex justify-center py-10">
                <UIcon class="animate-spin text-black/80" name="ant-design:loading-3-quarters-outlined" size="3rem"/>
              </div>
            </td>
          </tr>
          <tr v-show="!isLoading && data?.data.length === 0">
            <td class=" w-full" colspan="9">
              <div class="flex justify-center py-10">
                <p class="text-red-500 font-bold">No Data Found</p>
              </div>
            </td>
          </tr>
          <tr
              v-for="schoolAccount in data?.data" v-show="!isLoading" :key="schoolAccount.id"
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
              {{ schoolAccount?.admin_first_name }} {{ schoolAccount?.admin_last_name }}
            </td>
            <td class="px-6 py-4 capitalize">
              {{ schoolAccount?.school_email_address }}
            </td>
            <td class="px-6 py-4 capitalize">
              {{ schoolAccount?.school_contact_number }}
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center">
          
                <UBadge variant="subtle" :color="schoolAccount?.status === 'approved' ? 'success' : schoolAccount.status === 'pending'  ?  'warning' : schoolAccount.status === 'validating'  ?  'secondary' : 'error'" class="capitalize font-bold">{{ schoolAccount?.status }}</UBadge>
              </div>

            </td>
            <td class="px-6 py-4">
              <QadValidateSchool  :school_id="schoolAccount.id" :label="schoolAccount.status === 'approved' ? 'Update' : 'Validate'" class="font-bold"/>
              <QadSchoolAccountQadChangePasswordSchool :school-id="schoolAccount.id"/>
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
import QadNewSchool from "~/components/Qad/SchoolAccount/QadNewSchool.vue";
import {fetchSchoolAccount} from "#shared/API/Qad/SchoolAccountApi";
import QadNewBulkSchool from "~/components/Qad/SchoolAccount/QadNewBulkSchool.vue";
import QadValidateSchool from "~/components/Qad/SchoolAccount/QadValidateSchool.vue";

const route = useRoute();
const queryClient = useQueryClient();
const page = ref<number>(parseInt(route?.query?.page as string) || 1);
const search = ref<string>('');
const sort = ref<string>('school_name')
const direction = ref<string>('asc')
const {data, isLoading} = useQuery({
  queryKey: ['QAD_SCHOOL_ACCOUNT', page, direction],
  queryFn: () => fetchSchoolAccount(page, search.value, sort, direction),

})
watch(() => search.value, debounce(() => {
  queryClient.invalidateQueries({queryKey: ["QAD_SCHOOL_ACCOUNT"]});
}, 300))
watch(() => page.value, (page) => {
  navigateTo({name: 'Qad-School-Account', query: {page: page}})
})
const sortColumn = (column: string) => {
  direction.value = direction.value === 'asc' ? 'desc' : 'asc';
  sort.value = column
}
definePageMeta({
  middleware: ['qad-middleware'],
  layout: 'adminlayout',
});
</script>