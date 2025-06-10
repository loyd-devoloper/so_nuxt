<template>
  <div class="flex min-h-screen max-h-[100svh]">
    <UModal :dismissible="false" :open="authStore.logging_out" class="w-fit shadow-md">

      <template #content>
        <div class="p-10 flex items-center gap-2">
          <UIcon name="eos-icons:loading" size="40"/>
          Logging out...
        </div>
      </template>
    </UModal>
    <!--left sidebar -->
    <aside class="border-r border-black/10 bg-white    min-w-[16rem] max-w-[16rem] ">
      <header class="py-5">
        <NuxtImg alt="logo" class=" mx-auto" height="250" loading="lazy" src="/logo.svg" width="250"/>
      </header>
      <div>

        <UNavigationMenu
            :items="sidebarItem" :ui="{

            link: 'text-sm  font-normal text-muted data-active:font-medium before:rounded-none data-active:before:border-l-4 data-active:text-red-500  data-active:before:border-red-500 data-active:rounded-lg',

            childList: 'py-1'
          }" class="data-[orientation=vertical] " default-value="1"
            orientation="vertical"/>
      </div>
    </aside>

    <main class=" w-full max-w-[calc(100svw-16rem)]">
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
      <section class="px-5 py-5 max-h-[calc(100svh-4rem)] min-h-[calc(100svh-4rem)] overflow-y-auto bg-gray-50">
        <UBreadcrumb :items="breadcrumbs" class="text-xs">
          <template #separator>
            <span class="mx-2 text-muted">/</span>
          </template>
        </UBreadcrumb>
        <main class=" px-10 pt-16">
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
import type {DropdownMenuItem, NavigationMenuItem} from '@nuxt/ui'
import {useAuthStore} from "~/stores/AuthStore";

const route = useRoute();

const router = useRouter();
const authStore = useAuthStore();
const breadcrumbs = computed(() => {
  const paths = route.path.split('/').filter(Boolean)
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const crumbs: any = []

  // Add dynamic breadcrumbs
  let accumulatedPath = ''
  paths.forEach((path) => {
    accumulatedPath += `/${path}`
    const routeMatch = router.resolve(accumulatedPath)

    if (path !== 'Dashboard') {
      crumbs.push({
        label: path.replace(/-/g, ' ').toUpperCase() === 'QAD' ? 'Home' : path.replace(/-/g, ' '),
        icon: path.replace(/-/g, ' ').toUpperCase() === 'QAD' ? 'i-heroicons-home' : '',
        to: path.replace(/-/g, ' ').toUpperCase() === 'QAD' ? {name: 'Qad-Dashboard'} : routeMatch.name ? accumulatedPath : undefined,
        disabled: !routeMatch.name
      })
    }
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
        authStore.logout(localStorage.getItem('role') || 'qad')
      },
    }
  ],
])
const sidebarItem = computed(() => [

  {
    label: 'Dashboard',
    icon: 'material-symbols-light:dashboard-rounded',
    active: route.name == 'Qad-Dashboard',
    to: {name: 'Qad-Dashboard'},


  },
  {
    label: 'Accounts',
    icon: 'ic:twotone-manage-accounts',
    active: route.name == 'Qad-School-Account' || route.name == 'Qad-SDO-Account',
    defaultOpen: route.name == 'Qad-School-Account' || route.name == 'Qad-SDO-Account',
    collapsible: true,
    type: 'trigger',
    children: [
      {
        label: 'School Account',
        icon: 'ph:dot-outline',
        active: route.name == 'Qad-School-Account',
        to: {name: 'Qad-School-Account'},
        type: 'link',
      },
      {
        label: 'SDO Account',
        icon: 'ph:dot-outline',
        active: route.name == 'Qad-SDO-Account',
        type: 'link',
        onSelect: () => {
          navigateTo({name: 'Qad-SDO-Account'})
        }
      }
    ]
  },
  {
    label: 'Curriculum Management',
    icon: 'streamline:quality-education',
    active: route.name == 'Qad-Curriculum-Management' || route.name == 'Qad-Curriculum-Management-Programs-curriculum_id',
    to: {name: 'Qad-Curriculum-Management'}

  },
  {
    label: 'Transaction',
    icon: 'streamline:quality-education',
    active: route.name == 'Qad-Transaction',
    to: {name: 'Qad-Transaction'}

  },
  {
    label: 'Announcement',
    icon: 'streamline:quality-education',
    active: route.name == 'Qad-Announcement',
    to: {name: 'Qad-Announcement'}

  }, {
    label: 'Template',
    icon: 'streamline:quality-education',
    active: route.name == 'Qad-Template',
    to: {name: 'Qad-Template'}

  },
] as NavigationMenuItem[])

</script>