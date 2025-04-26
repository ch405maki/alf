<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Trash, Pencil } from 'lucide-vue-next'

defineProps<{
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
</script>

<template>
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-800">
        <tr>
          <th class="px-4 py-3 text-left text-sm font-medium">Title</th>
          <th class="px-4 py-3 text-left text-sm font-medium">Category</th>
          <th class="px-4 py-3 text-left text-sm font-medium">Author</th>
          <th class="px-4 py-3 text-left text-sm font-medium">Department</th>
          <th class="px-4 py-3 text-left text-sm font-medium">Status</th>
          <th class="px-4 py-3 text-left text-sm font-medium">Date</th>
          <th class="px-4 py-3 text-left text-sm font-medium">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        <tr v-for="post in posts" :key="post.id" class="hover:bg-muted/50">
          <td class="px-4 py-2">{{ post.title }}</td>
          <td class="px-4 py-2">{{ post.category }}</td>
          <td class="px-4 py-2">{{ post.user.name }}</td>
          <td class="px-4 py-2">{{ post.department?.name || '-' }}</td>
          <td class="px-4 py-2">
            <span :class="{
              'bg-green-100 text-green-800': post.status === 'published',
              'bg-yellow-100 text-yellow-800': post.status === 'draft',
              'px-2 py-1 rounded-full text-xs': true
            }">
              {{ post.status }}
            </span>
          </td>
          <td class="px-4 py-2">{{ new Date(post.created_at).toLocaleDateString() }}</td>
          <td class="px-4 py-2">
            <div class="flex gap-2">
              <Button variant="ghost" size="sm">
                <Pencil class="w-4 h-4" />
              </Button>
              <Button variant="ghost" size="sm" class="text-red-500 hover:text-red-700">
                <Trash class="w-4 h-4" />
              </Button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>