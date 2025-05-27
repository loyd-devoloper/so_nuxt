<template>

  <NuxtLayout name="adminlayout">



    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
      <UCard>
        <template #header>
          <Placeholder class="h-8" />
          <QadNewStudent/>
        </template>

        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
          <tr>
            <th scope="col" class="px-6 py-3">
              Product name
            </th>
            <th scope="col" class="px-6 py-3">
              Color
            </th>
            <th scope="col" class="px-6 py-3">
              Category
            </th>
            <th scope="col" class="px-6 py-3">
              Price
            </th>
            <th scope="col" class="px-6 py-3">
              Action
            </th>
          </tr>
          </thead>
          <tbody>
          <tr class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
              Apple MacBook Pro 17"
            </th>
            <td class="px-6 py-4">
              Silver
            </td>
            <td class="px-6 py-4">
              Laptop
            </td>
            <td class="px-6 py-4">
              $2999
            </td>
            <td class="px-6 py-4">
              <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
            </td>
          </tr>
          <tr class="odd:bg-white even:bg-gray-50  border-b border-gray-200">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
              Microsoft Surface Pro
            </th>
            <td class="px-6 py-4">
              White
            </td>
            <td class="px-6 py-4">
              Laptop PC
            </td>
            <td class="px-6 py-4">
              $1999
            </td>
            <td class="px-6 py-4">
              <a href="#" class="font-medium text-blue-60 hover:underline">Edit</a>
            </td>
          </tr>
          </tbody>
        </table>
      </UCard>

    </div>

  </NuxtLayout>

</template>
<script setup lang="ts">
import QadNewStudent from "~/components/Qad/SchoolAccount/QadNewStudent.vue";

definePageMeta({
  middleware: ['qad-middleware'],
});
</script>