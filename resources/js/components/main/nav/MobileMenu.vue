<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from '@/components/ui/sheet';
import { Menu } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';

defineProps<{
  items: Array<{
    title: string
    href?: string
    children?: Array<{
      title: string
      href: string
    }>
  }>
  activeItemStyles: (url: string) => string
}>();
</script>

<template>
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
            <template v-for="item in items" :key="item.title">
              <div v-if="!item.children">
                <Link
                  v-if="item.href"
                  :href="item.href"
                  class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent"
                  :class="activeItemStyles(item.href)"
                >
                  {{ item.title }}
                </Link>
              </div>
              <div v-else>
                <div class="px-3 py-2 text-sm font-medium">{{ item.title }}</div>
                <div class="pl-4">
                  <Link
                    v-for="child in item.children"
                    :key="child.title"
                    :href="child.href"
                    class="flex items-center gap-x-3 rounded-lg px-3 py-2 text-sm font-medium hover:bg-accent"
                    :class="activeItemStyles(child.href)"
                  >
                    {{ child.title }}
                  </Link>
                </div>
              </div>
            </template>
          </nav>
        </div>
      </SheetContent>
    </Sheet>
  </div>
</template>