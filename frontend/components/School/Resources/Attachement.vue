<template>

  <div>

    <div>
      <div>
        <div class="flex justify-between items-center gap-2 py-5">


          <UInput v-model="search" icon="i-lucide-search" placeholder="Filter..." size="md" type="search"
            variant="outline" />


        </div>
      </div>

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
                Uploaded At
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
            <tr v-for="attachment in data?.data" v-show="!isLoading" :key="attachment.id"
              class="odd:bg-white  even:bg-gray-50  border-b  border-gray-200">
              <th class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white" scope="row">
                {{ attachment?.document_name
                }}
              </th>


              <td class="px-6 py-4 space-x-2">

                {{ moment(attachment.created_at).format('MMMM D YYYY, h:mm:ss A') }}
              </td>


              <td class="px-6 py-4 space-x-2">

                <UButton label="Download" size="sm" icon="material-symbols:download" color="info" variant="outline"
                  @click="downloadAttachmentFunc({ attachment_id: attachment.id, name: attachment.document_name })" />
              </td>
            </tr>

          </tbody>
        </table>
      </div>


      <UPagination v-model:page="page" :items-per-page="data?.per_page" :sibling-count="1" :total="data?.total"
        class="pt-10  w-fit mx-auto " show-edges />

    </div>



  </div>
</template>
<script setup lang="ts">
import debounce from 'lodash.debounce'
import { downloadAttachment, fetchAttachment } from '~/shared/API/School/ResourcesApi';
import moment from 'moment';
const route = useRoute();
const queryClient = useQueryClient();
const page = ref<number>(parseInt(route?.query?.page as string) || 1);
const search = ref<string>('');
const sort = ref<string>('')
const direction = ref<string>('desc')
const { data, isLoading } = useQuery({
  queryKey: ['SCHOOL_RESOURCES_ATTACHMENT', page, direction],
  queryFn: () => fetchAttachment(page, search.value, sort, direction),
})
watch(() => search.value, debounce(() => {
  queryClient.invalidateQueries({ queryKey: ["SCHOOL_RESOURCES_ATTACHMENT"] });
}, 300))

const sortColumn = (column: string) => {
  direction.value = direction.value === 'asc' ? 'desc' : 'asc';
  sort.value = column
}
const { mutate: downloadAttachmentFunc } = useMutation({
  mutationFn: (data: { attachment_id: string, name: string }) => downloadAttachment(data.attachment_id, data.name),

})
</script>