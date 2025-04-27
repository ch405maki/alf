<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import PostsTable from '@/components/posts/PostsTable.vue';
import { router } from '@inertiajs/vue3'
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Posts', href: '/posts' }
]
const props = defineProps<{
  posts: Array<{
    id: number
    title: string
    category: string
    content: string
    status: string
    user: {
      name: string
    }
    department?: {
      name: string
    }
    created_at: string
  }>
}>()

const create = () => {
  router.visit(route('posts.create'))
}

const handleDelete = () => {
  router.reload({ only: ['posts'] })
}
</script>

<template>
  <Head title="Posts" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="flex justify-end items-center">
            <Button @click="create()">Create</Button>
        </div>
        <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
          <PostsTable :posts="posts" @delete="handleDelete" />
        </div>
    </div>
  </AppLayout>
</template>