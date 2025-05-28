<template>
  <div class="flex min-h-screen max-h-[100svh]">
    <UModal :dismissible="false" :open="authStore.logging_out" class="w-fit">

      <template #content>
        <div class="p-10 flex items-center gap-2">
          <UIcon name="eos-icons:loading" size="40"/>
          Logging out...
        </div>
      </template>
    </UModal>
    <!--left sidebar -->
    <aside class="border-r border-black/10 bg-white    min-w-[15rem] max-w-[15rem] ">
      <header class="py-5">
        <NuxtImg alt="logo" class=" mx-auto" height="250" loading="lazy" src="/logo.svg" width="250"/>
      </header>
      <div class=" px-4">

        <UNavigationMenu :items="sidebarItem" class="data-[orientation=vertical]" default-value="1"
                         orientation="vertical"/>
      </div>
    </aside>

    <main class="w-full ">
      <nav class="bg-white h-[4rem] border-b border-black/10 flex justify-end items-center px-4  ">
        <UDropdownMenu
            :items="profile"
            :ui="{
      content: 'w-48'
    }"
        >
          <UButton color="neutral" icon="lets-icons:setting-line" variant="ghost"/>
        </UDropdownMenu>
      </nav>

      <!-- main content -->
      <section class="px-5 py-5">
        <UBreadcrumb :items="items">
          <template #separator>
            <span class="mx-2 text-muted">/</span>
          </template>
        </UBreadcrumb>
        <main class="pt-10">
          <slot/>
        </main>
      </section>
    </main>
  </div>
</template>
<script lang="ts" setup>
import type {BreadcrumbItem, DropdownMenuItem,NavigationMenuItem} from '@nuxt/ui'
import {useAuthStore} from "~/stores/AuthStore";

const route = useRoute();
const authStore = useAuthStore();
onMounted(() =>{
  console.log(route.name)
})
const items: BreadcrumbItem[] = [
  {
    label: 'Home',
    to: '/'
  },
  {
    label: 'Components',
    to: '/components'
  },
  {
    label: 'Breadcrumb',
    to: '/components/breadcrumb'
  }
]
const profile = ref<DropdownMenuItem[][]>([
  [
    {

      label: authStore.authUser?.email,

    },
    {
      label: 'Account Setting',
      icon: 'tdesign:user-setting',
    },
    {
      label: 'Logout',
      icon: 'material-symbols:logout-rounded',
      onSelect: () => {
        authStore.logout(localStorage.getItem('role'))
      },
    }
  ],
])
const sidebarItem = computed(() => [

  {
    label: 'Dashboard',
    icon: 'material-symbols-light:dashboard-rounded',
    active: route.name == 'Qad-Dashboard',
    to:{name: 'Qad-Dashboard'}

  },
  {
    label:'Accounts',
    icon: 'ic:twotone-manage-accounts',
    active: route.name == 'Qad-School-Account' || route.name == 'Qad-SDO-Account',
    defaultOpen: route.name == 'Qad-School-Account' || route.name == 'Qad-SDO-Account',
    collapsible: true,
    type:'trigger',
    children:[
      {
        label: 'School Account',
        icon: 'ph:dot-outline',
        active: route.name == 'Qad-School-Account',
        to: {name: 'Qad-School-Account'},
        type:'link',
      },
      {
        label: 'SDO Account',
        icon: 'ph:dot-outline',
        active: route.name == 'Qad-SDO-Account',
        type:'link',
        // to: {name: 'Qad-SDO-Account'},
        onSelect:() =>{
          navigateTo({name: 'Qad-SDO-Account'})
        }
      }
    ]
  }

])

</script>