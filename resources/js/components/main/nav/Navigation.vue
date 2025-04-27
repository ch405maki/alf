<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import DesktopMenu from '@/components/main/nav/DesktopMenu.vue';
import MobileMenu from '@/components/main/nav/MobileMenu.vue';
import type { BreadcrumbItem } from '@/types';

interface Props {
  breadcrumbs?: BreadcrumbItem[];
}

const props = withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});

const page = usePage();
const auth = computed(() => page.props.auth);

const isCurrentRoute = computed(() => (url: string) => page.url === url);

const activeItemStyles = computed(() => (url: string) =>
  isCurrentRoute.value(url) ? 'text-neutral-900' : ''
);

const adminMenuItems = [
  { title: 'Board of Trustees', href: '/board-of-trustees' },
  { title: 'Arellano Law Foundation', href: '/arellano-law-foundation' },
  {
    title: 'Departments',
    children: [
      { title: 'ITC', href: '/departments/itc' },
      { title: 'Accounting', href: '/departments/accounting' },
    ],
  },
];
</script>

<template>
  <div>
    <!-- Top Navbar -->
    <div class="border-b border-sidebar-border/80 bg-official-purple-900">
      <div class="mx-auto flex h-16 items-center px-4 md:max-w-6xl">
        <!-- Mobile Menu -->
        <MobileMenu :items="adminMenuItems" :active-item-styles="activeItemStyles" />
        
        <!-- Logo -->
        <Link :href="route('home')" class="flex items-center gap-x-2">
          <div class="flex aspect-square size-8 items-center justify-center rounded-md text-sidebar-primary-foreground">
            <img src="/images/logo/logo.png" alt="ALF Logo">
          </div>
          <div class="ml-1 grid flex-1 text-left text-sm">
            <span class="mb-0.5 truncate font-semibold leading-none text-white">
              <h1 class="font-times text-sm md:text-base lg:text-lg xl:text-lg text-transparent bg-clip-text bg-gradient-to-r from-[#FFD700] to-[#FFC400] uppercase font-semibold">
                Arellano Law Foundation
              </h1>
            </span>
          </div>
        </Link>

        <!-- Desktop Menu -->
        <DesktopMenu :items="adminMenuItems" />
      </div>
    </div>

    <!-- Breadcrumbs -->
    <div
      v-if="props.breadcrumbs.length > 1"
      class="flex w-full border-b border-sidebar-border/70"
    >
      <div class="mx-auto flex h-12 w-full items-center justify-start px-4 text-neutral-500 md:max-w-7xl">
        <Breadcrumbs :breadcrumbs="breadcrumbs" />
      </div>
    </div>
  </div>
</template>