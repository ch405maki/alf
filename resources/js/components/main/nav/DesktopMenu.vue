<script setup lang="ts">
import { Menubar, MenubarMenu, MenubarTrigger, MenubarContent, MenubarItem, MenubarSub, MenubarSubTrigger, MenubarSubContent, MenubarSeparator } from '@/components/ui/menubar';
import { Link } from '@inertiajs/vue3';

defineProps<{
  items: Array<{
    title: string
    href?: string
    children?: Array<{
      title: string
      href: string
    }>
  }>
}>();
</script>

<template>
  <div class="ml-auto hidden lg:flex items-center space-x-2">
    <Menubar class="h-9">
      <MenubarMenu>
        <MenubarTrigger>Administration</MenubarTrigger>
        <MenubarContent class="w-56">
          <template v-for="(item, index) in items" :key="index">
            <template v-if="!item.children">
              <MenubarItem as-child>
                <Link :href="item.href" class="w-full">
                  {{ item.title }}
                </Link>
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
                    <Link :href="child.href" class="w-full">
                      {{ child.title }}
                    </Link>
                  </MenubarItem>
                </MenubarSubContent>
              </MenubarSub>
            </template>

            <MenubarSeparator />
          </template>
        </MenubarContent>
      </MenubarMenu>
    </Menubar>
  </div>
</template>