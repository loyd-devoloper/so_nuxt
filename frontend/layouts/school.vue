<template>
  <div class="flex min-h-screen max-h-[100svh]">
    <First_time_login/>
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

        <UNavigationMenu
:items="sidebarItem" class="data-[orientation=vertical]" default-value="1"
                         orientation="vertical"/>
      </div>
    </aside>

    <main class="  w-full max-w-[calc(100svw-15rem)]">
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
      <section class="px-5 py-5 max-h-[calc(100svh-4rem)] overflow-y-auto">
        <UBreadcrumb :items="breadcrumbs" class="text-xs">
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
import type {BreadcrumbItem, DropdownMenuItem} from '@nuxt/ui'
import {useAuthStore} from "~/stores/AuthStore";
import First_time_login from "~/components/School/first_time_login.vue";


const authStore = useAuthStore();
const route = useRoute()
const router = useRouter()

const breadcrumbs = computed(() => {
  const paths = route.path.split('/').filter(Boolean)
  const crumbs = []
  
  // Add home breadcrumb
  crumbs.push({
    label: 'Home',
    icon: 'i-heroicons-home',
    to: '/'
  })
  
  // Add dynamic breadcrumbs
  let accumulatedPath = ''
  paths.forEach((path) => {
    accumulatedPath += `/${path}`
    const routeMatch = router.resolve(accumulatedPath)
    
    crumbs.push({
      label: path.replace(/-/g, ' ').toUpperCase(),
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
    to:{name: 'School'}

  },  {
    label: 'Transaction',
    icon: 'icon-park-outline:transaction',
    active: route.name == 'School-Transaction' || route.name == 'School-Transaction-For-Releasing' || route.name == 'School-Transaction' || route.name == 'School-Transaction-Released' ||  route.name == 'School-Transaction-Students-application_id',
    defaultOpen: route.name == 'School-Transaction' || route.name == 'School-Transaction-For-Releasing' || route.name == 'School-Transaction' || route.name == 'School-Transaction-Released' ||  route.name == 'School-Transaction-Students-application_id',
    children:[
      {
        label: 'SO Application',
        icon: 'ph:dot-outline',
        active: route.name == 'School-Transaction' ||  route.name == 'School-Transaction-Students-application_id',
        to: {name: 'School-Transaction'},
        type:'link',
      },
      {
        label: 'For Releasing',
        icon: 'ph:dot-outline',
        active: route.name == 'School-Transaction-For-Releasing',
        type:'link',
        to: {name: 'School-Transaction-For-Releasing'},

      },
      {
        label: 'Released',
        icon: 'ph:dot-outline',
        active: route.name == 'Qad-SDO-Account',
        type:'link',
        to: {name: 'School-Transaction-Released'},
      }
    ]

  },


])

</script>