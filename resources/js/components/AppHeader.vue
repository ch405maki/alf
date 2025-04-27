<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Menu } from 'lucide-vue-next';

import AppLogo from '@/components/AppLogo.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';

import {
  Menubar,
  MenubarMenu,
  MenubarTrigger,
  MenubarContent,
  MenubarItem,
  MenubarSub,
  MenubarSubTrigger,
  MenubarSubContent,
  MenubarSeparator,
} from '@/components/ui/menubar';

import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';

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

// Separate menu groups
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

const programMenuItems = [
  { title: 'AUSL', href: '/programs/ausl' },
  { title: 'Prebar', href: '/programs/prebar' },
  { title: 'MCLE', href: '/programs/mcle' },
];
</script>

<template>
  <div>
    <!-- Top Navbar -->
    <div class="border-b border-sidebar-border/80 bg-official-purple-900">
      <div class="mx-auto flex h-16 items-center px-4 md:max-w-6xl">

        <!-- Mobile Menu -->
        <div class="lg:hidden">
          <Sheet>
            <SheetTrigger as-child>
              <Button variant="ghost" size="icon" class="mr-2 h-9 w-9">
                <Menu class="h-5 w-5" />
              </Button>
            </SheetTrigger>

            <SheetContent side="left" class="w-[300px] p-6">
              <SheetHeader class="flex justify-start text-left">
                <AppLogoIcon class="size-6 fill-current text-black" />
              </SheetHeader>

              <div class="flex h-full flex-1 flex-col justify-between space-y-4 py-6">
                <nav class="-mx-3 space-y-1">
                  <Link
                    v-for="item in [...adminMenuItems, ...programMenuItems]"
                    :key="item.title"
                    :href="item.href"
                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent"
                    :class="activeItemStyles(item.href)"
                  >
                    {{ item.title }}
                  </Link>
                </nav>
              </div>
            </SheetContent>
          </Sheet>
        </div>

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
        <div class="ml-auto hidden lg:flex items-center space-x-6">
          <Menubar>
            
            <!-- Administration Menu -->
            <MenubarMenu >
                <MenubarTrigger >
                    Administration
                </MenubarTrigger>

              <MenubarContent class="w-56">
                <template v-for="(item, index) in adminMenuItems" :key="index">
                  <template v-if="!item.children">
                    <MenubarItem as-child>
                      <Link :href="item.href" class="w-full">{{ item.title }}</Link>
                    </MenubarItem>
                  </template>
                  <template v-else>
                    <MenubarSub>
                      <MenubarSubTrigger>{{ item.title }}</MenubarSubTrigger>
                      <MenubarSubContent>
                        <MenubarItem
                          v-for="(child, cIndex) in item.children"
                          :key="cIndex"
                          as-child
                        >
                          <Link :href="child.href" class="w-full">{{ child.title }}</Link>
                        </MenubarItem>
                      </MenubarSubContent>
                    </MenubarSub>
                  </template>
                  <MenubarSeparator />
                </template>
              </MenubarContent>
            </MenubarMenu>

            <!-- Programs Menu -->
            <MenubarMenu>
              <MenubarTrigger>Programs</MenubarTrigger>
              <MenubarContent class="w-56">
                <template v-for="(program, index) in programMenuItems" :key="index">
                  <MenubarItem as-child>
                    <Link :href="program.href" class="w-full">
                      {{ program.title }}
                    </Link>
                  </MenubarItem>
                  <MenubarSeparator v-if="index !== programMenuItems.length - 1" />
                </template>
              </MenubarContent>
            </MenubarMenu>
          </Menubar>
        </div>
      </div>
    </div>

    <!-- Breadcrumbs -->
    <div v-if="props.breadcrumbs.length > 1" class="flex w-full border-b border-sidebar-border/70">
      <div class="mx-auto flex h-12 w-full items-center justify-start px-4 text-neutral-500 md:max-w-7xl">
        <Breadcrumbs :breadcrumbs="breadcrumbs" />
      </div>
    </div>
  </div>
</template>
