<template>
  <div class="flex min-h-screen max-h-[100svh]">
    <UModal :open="authStore.logging_out" :dismissible="false" class="w-fit">

      <template #content>
        <div class="p-10 flex items-center gap-2">
          <UIcon name="eos-icons:loading" size="40" />
          Logging out...
        </div>
      </template>
    </UModal>
    <!--left sidebar -->
    <aside class="border-r border-black/10 bg-white    min-w-[15rem] max-w-[15rem] ">
      <header class="py-5">
        <NuxtImg src="/logo.svg" alt="logo" class=" mx-auto" loading="lazy" width="250" height="250"/>
      </header>
      <div class=" px-4">

        <UNavigationMenu orientation="vertical" default-value="1" :items="sidebarItem" class="data-[orientation=vertical]" />
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
          <UButton icon="lets-icons:setting-line" color="neutral" variant="ghost"/>
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
<script setup lang="ts">
import type {BreadcrumbItem, DropdownMenuItem,NavigationMenuItem } from '@nuxt/ui'
import {useAuthStore} from "~/stores/AuthStore";

const route = useRoute();
const authStore = useAuthStore();

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

      label: authStore.authUser?.email_address,
      title: authStore.authUser?.email_address
    },
    {
      label: 'Account Setting',
      icon: 'tdesign:user-setting',
    },
    {
      label: 'Logout',
      icon: 'material-symbols:logout-rounded',
      onSelect: () =>{
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
      to: {name: 'Qad-Dashboard'},

    },
    {
      label: 'School Account',
      icon: 'mdi:accounts-group-outline',
      active: route.name == 'Qad-School-Account',
      to: {name: 'Qad-School-Account'},

    }

])
</script>