<template>

  <div>

    <UCard class="shadow-none border border-black/10 drop-shadow-none">
      <template #header class="pb-4">
        <div class="flex justify-between items-center gap-2">


          <UInput v-model="search" icon="i-lucide-search" placeholder="Filter..." size="md" type="search"
            variant="outline" />
          <SchoolNewApplication />


        </div>
      </template>

      <div class="overflow-x-auto">
        <table class="w-full  text-sm text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
            <tr>
              <th class="px-6 py-3 " scope="col">

                <div class="flex items-center gap-2">
                  School Year
                  <UIcon class="cursor-pointer hover:text-black" name="lsicon:sort-filled" size="1rem"
                    @click="sortColumn('school_year_end')" />
                </div>
              </th>
              <th class="px-6 py-3" scope="col">
                <div class="flex items-center gap-2">
                  Applied Track
                  <UIcon class="cursor-pointer hover:text-black" name="lsicon:sort-filled" size="1rem"
                    @click="sortColumn('applied_track')" />
                </div>
              </th>
              <th class="px-6 py-3" scope="col">
                Applied Strand
              </th>
              <th class="px-6 py-3" scope="col">
                Applied Specialization
              </th>
              <th class="px-6 py-3" scope="col">
                Status
              </th>
              <th class="px-6 py-3" scope="col">
                No. of Student

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
                  <UIcon class="animate-spin text-black/80" name="ant-design:loading-3-quarters-outlined" size="3rem" />
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
            <tr v-for="application in data?.data" v-show="!isLoading" :key="application.id"
              class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
              <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" scope="row">
                {{ application?.curriculum_info?.school_year_start + ' - ' +
                  application?.curriculum_info?.school_year_end
                }}
              </th>
              <td class="px-6 py-4">
                {{ application?.applied_track }}
              </td>
              <td class="px-6 py-4">
                {{ application?.applied_strand }}
              </td>
              <td class="px-6 py-4">
                <li v-for="spec in JSON.parse(application?.applied_specialization) ?? []" :key="spec">{{ spec }}</li>
              </td>
              <td class="px-6 py-4">
                <div class="flex items-center">

                  <UBadge variant="outline" class="capitalize" :color="getStatusColor(application?.status)">{{
                    application?.status }}</UBadge>
                </div>

              </td>
              <td class="px-6 py-4">
                <UBadge color="info">{{ application?.students_count }}</UBadge>
              </td>


              <td class="px-6 py-4">
               
                    <div v-if="application.status === 'approved' || application.status === 'releasing' || application.status === 'for_claim' || application.status === 'released'" class=" w-full">
                      <QadTransactionQadPreviewSo :application_id="application.id">
                        <UButton color="neutral" icon="qlementine-icons:preview-16" label="Generate So" size="sm"
                          type="button" variant="ghost" />
                      </QadTransactionQadPreviewSo>
                    </div>
                    <div  class=" w-full">
                      <nuxt-link :to="{
                        name: 'School-Transaction-Students-application_id',
                        params: { application_id: application.id } // Using the dynamic value here
                      }">
                        <UButton label="Students" icon="hugeicons:students" color="neutral" size="sm" type="button"
                          variant="ghost" />
                      </nuxt-link>
                    </div>

               
              

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
  middleware: ['school-middleware'],
  layout: 'school',
});
import debounce from 'lodash.debounce'
import SchoolNewApplication from "~/components/School/Transaction/SchoolNewApplication.vue";
import { fetchSchoolApplication } from '~/shared/API/School/TransactionApi';
import type { DropdownMenuItem } from '@nuxt/ui'
// const items = (id: string): DropdownMenuItem[] => [
//   {
//     label: 'Profile',
//     icon: 'i-lucide-user'
//   },
//   {
//     label: 'So Preview',
//     type: 'label',
//     icon: 'qlementine-icons:preview-16'
//   },
//   {
//     label: 'Students',
//     to: {
//       name: 'School-Transaction-Students-application_id',
//       params: { application_id: id } // Using the dynamic value here
//     },
//     icon: 'hugeicons:students'
//   }
// ]
const getStatusColor = (status: string) => {
  const statusColors: any = {
    approved: 'success',
    pending: 'warning',
    onprocess: 'info',
    releasing: 'success',
    for_claim: 'success',
    released: 'success'
  }
  return statusColors[status] || 'gray'
}
const route = useRoute();
const queryClient = useQueryClient();
const page = ref<number>(parseInt(route?.query?.page as string) || 1);
const search = ref<string>('');
const sort = ref<string>('')
const direction = ref<string>('asc')
const { data, isLoading } = useQuery({
  queryKey: ['SCHOOL_TRANSACTION', page, direction],
  queryFn: () => fetchSchoolApplication(page, search.value, sort, direction),
})
watch(() => search.value, debounce(() => {
  queryClient.invalidateQueries({ queryKey: ["SCHOOL_TRANSACTION"] });
}, 300))
watch(() => page.value, (page) => {
  navigateTo({ name: 'School-Transaction', query: { page: page } })
})
const sortColumn = (column: string) => {
  direction.value = direction.value === 'asc' ? 'desc' : 'asc';
  sort.value = column
}
</script>