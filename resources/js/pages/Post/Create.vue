<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import PostForm from '@/components/posts/PostForm.vue';
import type { Page } from '@inertiajs/core'; // Important import!

// 1. Define PageProps
interface PageProps {
  userId: number | string;
  userDepartmentId: number | string;
  departments: {
    id: number;
    name: string;
  }[];
}

// 2. Correctly type usePage()
const page = usePage<PageProps>()

// 3. Extract your values
const userId = page.props.userId;
const userDepartmentId = page.props.userDepartmentId;
const departments = page.props.departments;

// ✅ NO defineProps() needed! Use page.props instead!

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Posts', href: '/posts' },
  { title: 'Create', href: '/posts/create' }
]

const handleSuccess = () => {
  // Handle successful post creation
}
</script>

<template>
  <Head title="Create Post" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-6">
        <h2 class="text-xl font-semibold mb-6">Create New Post</h2>

        <PostForm 
          :userId="userId" 
          :userDepartmentId="userDepartmentId" 
          :departments="departments" 
          @success="handleSuccess"
        />
      </div>
    </div>
  </AppLayout>
</template>
