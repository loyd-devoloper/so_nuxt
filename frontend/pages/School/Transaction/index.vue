<template>

  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <UCard>
      <template #header>
        <div class="flex justify-end items-center gap-2">


            <UInput v-model="search" icon="i-lucide-search" placeholder="Search..." size="md" type="search"
                    variant="outline"/>
            <SchoolNewApplication/>


        </div>
      </template>

      <div class="overflow-x-auto">
        <table class="w-full  text-sm text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
          <tr>
            <th class="px-6 py-3 " scope="col">

              <div class="flex items-center gap-2">
                Curriculum Year
                <UIcon class="cursor-pointer hover:text-black" name="lsicon:sort-filled" size="1rem"
                       @click="sortColumn('school_name')"/>
              </div>
            </th>
            <th class="px-6 py-3" scope="col">
              <div class="flex items-center gap-2">
                Applied Track
                <UIcon class="cursor-pointer hover:text-black" name="lsicon:sort-filled" size="1rem"
                       @click="sortColumn('school_number')"/>
              </div>
            </th>
            <th class="px-6 py-3" scope="col">
              Applied Strand
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
<!--          <tbody>-->
<!--          <tr v-show="isLoading">-->
<!--            <td class=" w-full" colspan="8">-->
<!--              <div class="flex justify-center py-10">-->
<!--                <UIcon class="animate-spin text-black/80" name="ant-design:loading-3-quarters-outlined" size="3rem"/>-->
<!--              </div>-->
<!--            </td>-->
<!--          </tr>-->
<!--          <tr v-show="!isLoading && data?.data.length === 0">-->
<!--            <td class=" w-full" colspan="8">-->
<!--              <div class="flex justify-center py-10">-->
<!--                <p class="text-red-500 font-bold">No Data Found</p>-->
<!--              </div>-->
<!--            </td>-->
<!--          </tr>-->
<!--          <tr-->
<!--              v-for="schoolAccount in data?.data" v-show="!isLoading" :key="schoolAccount.id"-->
<!--              class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">-->
<!--            <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" scope="row">-->
<!--              {{ schoolAccount?.school_name }}-->
<!--            </th>-->
<!--            <td class="px-6 py-4">-->
<!--              {{ schoolAccount?.school_number }}-->
<!--            </td>-->
<!--            <td class="px-6 py-4">-->
<!--              {{ schoolAccount?.sdo_information?.sdo_name }}-->
<!--            </td>-->
<!--            <td class="px-6 py-4">-->
<!--              {{ schoolAccount?.school_address }}-->
<!--            </td>-->
<!--            <td class="px-6 py-4">-->
<!--              {{ schoolAccount?.admin_first_name }} {{ schoolAccount?.admin_last_name }}-->
<!--            </td>-->
<!--            <td class="px-6 py-4 capitalize">-->
<!--              {{ schoolAccount?.admin_contact_number }}-->
<!--            </td>-->
<!--            <td class="px-6 py-4 capitalize">-->
<!--              {{ schoolAccount?.type }}-->
<!--            </td>-->
<!--            <td class="px-6 py-4">-->
<!--              <div class="flex items-center">-->
<!--                <div-->
<!--                    :class="schoolAccount?.status === 'approved' ? ' bg-green-500' : schoolAccount.status === 'pending' ?  'bg-orange-400' : ' bg-red-500'"-->
<!--                    class="h-2.5 w-2.5 rounded-full me-2 "/>-->
<!--                <span class="capitalize">{{ schoolAccount?.status }}</span>-->
<!--              </div>-->

<!--            </td>-->
<!--            <td class="px-6 py-4">-->
<!--              <QadValidateSchool v-if="schoolAccount.status === 'approved' || schoolAccount.status === 'validating'" :school_id="schoolAccount.id" :label="schoolAccount.status === 'approved' ? 'Update' : 'Validate'"/>-->
<!--            </td>-->
<!--          </tr>-->

<!--          </tbody>-->
        </table>
      </div>

<!--      <UPagination-->
<!--          v-model:page="page" :items-per-page="data?.per_page" :sibling-count="1" :total="data?.total"-->
<!--          class="pt-10  w-fit mx-auto" show-edges/>-->

    </UCard>

  </div>
</template>
<script setup lang="ts">
import QadNewBulkSchool from "~/components/Qad/SchoolAccount/QadNewBulkSchool.vue";
import QadValidateSchool from "~/components/Qad/SchoolAccount/QadValidateSchool.vue";
import QadNewSchool from "~/components/Qad/SchoolAccount/QadNewSchool.vue";
import SchoolNewApplication from "~/components/School/Transaction/SchoolNewApplication.vue";

definePageMeta({
  middleware: ['school-middleware'],
  layout: 'school',
});

</script>