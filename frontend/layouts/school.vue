<template>
  <div class="flex min-h-screen max-h-[100svh]">
    <First_time_login />
    <UModal :dismissible="false" :open="authStore.logging_out" class="w-fit">

      <template #content>
        <div class="p-10 flex items-center gap-2">
          <UIcon name="eos-icons:loading" size="40" />
          Logging out...
        </div>
      </template>
    </UModal>
    <!--left sidebar -->
    <aside class="border-r border-black/10 bg-[#fcfcfc00]    min-w-[15rem] max-w-[15rem] ">
      <header class="py-5">
        <NuxtImg alt="logo" class=" mx-auto" height="250" loading="lazy" src="/logo.svg" width="250" />
      </header>
      <div >

        <UNavigationMenu
:items="sidebarItem" class="data-[orientation=vertical]" default-value="1"
          orientation="vertical" :ui="{
           
            link: 'text-sm  font-normal text-muted data-active:font-medium before:rounded-none data-active:before:border-l-4 data-active:text-primary data-active:before:text-primary data-active:rounded-lg',

            childList: 'py-1'
          }" />
      </div>
    </aside>

      <main class=" w-full max-w-[calc(100svw-16rem)]">
      <nav class="bg-white h-[4rem] border-b border-black/10 flex justify-end items-center px-4  ">
        
            <UButton color="neutral" icon="mdi:bell-outline" variant="ghost"/>
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
      <section class="px-5 py-5 max-h-[calc(100svh-4rem)] min-h-[calc(100svh-4rem)] overflow-y-auto bg-gray-50">
        <UBreadcrumb :items="breadcrumbs" class="text-xs">
          <template #separator>
            <span class="mx-2 text-muted">/</span>
          </template>
        </UBreadcrumb>
        <main class=" px-10 pt-10">
          <slot/>
          <footer class="pt-10 text-center">
            Copyright © Designed & Developed by Deped 2025
          </footer>
        </main>
      </section>
    </main>
  </div>
</template>
<script lang="ts" setup>
import type {  DropdownMenuItem } from '@nuxt/ui'
import { useAuthStore } from "~/stores/AuthStore";
import First_time_login from "~/components/School/first_time_login.vue";


const authStore = useAuthStore();
const route = useRoute()
const router = useRouter()

const breadcrumbs = computed(() => {
  const paths = route.path.split('/').filter(Boolean)
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const crumbs: any = []

  // Add home breadcrumb
  // crumbs.push({
  //   label: 'Home',
  //   icon: 'i-heroicons-home',
  //   to: '/'
  // })

  // Add dynamic breadcrumbs
  let accumulatedPath = ''
  paths.forEach((path) => {
    
    accumulatedPath += `/${path}`
    const routeMatch = router.resolve(accumulatedPath)

    crumbs.push({
      label: path.replace(/-/g, ' ').toUpperCase() === 'SCHOOL' ? 'Dashboard' : path.replace(/-/g, ' '),
      icon: path.replace(/-/g, ' ').toUpperCase() === 'SCHOOL' ? 'material-symbols-light:dashboard-rounded' : '',
      to: routeMatch.name ? accumulatedPath : undefined,
      disabled: !routeMatch.name
    })
  })

  return crumbs
})

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
        authStore.logout('School')
      },
    }
  ],
])
const sidebarItem = computed(() => [

  {
    label: 'Dashboard',
    icon: 'material-symbols-light:dashboard-rounded',
    active: route.name == 'School',
    to: { name: 'School' }

  },
   {
    label: 'Transaction',
    icon: 'icon-park-outline:transaction',
    active: route.name == 'School-Transaction' || route.name == 'School-Transaction-For-Releasing' || route.name == 'School-Transaction' || route.name == 'School-Transaction-Released' || route.name == 'School-Transaction-Students-application_id',
    defaultOpen: route.name == 'School-Transaction' || route.name == 'School-Transaction-For-Releasing' || route.name == 'School-Transaction' || route.name == 'School-Transaction-Released' || route.name == 'School-Transaction-Students-application_id',
    children: [
      {
        label: 'SO Application',
        icon: 'ph:dot-outline',
        active: route.name == 'School-Transaction' || route.name == 'School-Transaction-Students-application_id',
        to: { name: 'School-Transaction' },
        type: 'link',
      },
      {
        label: 'For Releasing',
        icon: 'ph:dot-outline',
        active: route.name == 'School-Transaction-For-Releasing',
        type: 'link',
        to: { name: 'School-Transaction-For-Releasing' },

      },
      {
        label: 'Released',
        icon: 'ph:dot-outline',
        active: route.name == 'School-Transaction-Released',
        type: 'link',
        to: { name: 'School-Transaction-Released' },
      }
    ]

  },
   {
    label: 'Resources',
    icon: 'grommet-icons:resources',
    active: route.name == 'School-Resources',
    to: { name: 'School-Resources' }

  },


])

</script>