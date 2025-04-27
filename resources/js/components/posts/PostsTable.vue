<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { Button } from '@/components/ui/button'
import { Trash, Pencil } from 'lucide-vue-next'
import { useToast } from 'vue-toastification'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { Badge } from '@/components/ui/badge'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'

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

const toast = useToast()

const emit = defineEmits<{
  (e: 'edit', id: number): void
  (e: 'delete', id: number): void
}>()

const showDeleteDialog = ref(false)
const postToDelete = ref<number | null>(null)

const openDeleteDialog = (id: number) => {
  postToDelete.value = id
  showDeleteDialog.value = true
}

const confirmDelete = async () => {
  if (!postToDelete.value) return

  try {
    await axios.delete(`/api/posts/${postToDelete.value}`)
    emit('delete', postToDelete.value)
    toast.success('Post deleted successfully.')
    showDeleteDialog.value = false
  } catch (error) {
    console.error('Error deleting post:', error)
    toast.error('Failed to delete post.')
  }
}

</script>

<template>
  <div class="rounded-md border">
    <Table>
      <TableHeader>
        <TableRow>
          <TableHead class="w-[300px]">Title</TableHead>
          <TableHead>Category</TableHead>
          <TableHead>Author</TableHead>
          <TableHead>Department</TableHead>
          <TableHead>Status</TableHead>
          <TableHead class="text-right">Actions</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="post in posts" :key="post.id">
          <TableCell class="font-medium">
            <div class="flex flex-col">
              <span>{{ post.title }}</span>
              <span class="text-xs text-muted-foreground">
                {{ new Date(post.created_at).toLocaleDateString('en-PH', { 
                  month: 'long', 
                  day: 'numeric', 
                  year: 'numeric' 
                }) }}
              </span>
            </div>
          </TableCell>
          <TableCell>{{ post.category }}</TableCell>
          <TableCell>{{ post.user.name }}</TableCell>
          <TableCell>{{ post.department?.name || '-' }}</TableCell>
          <TableCell>
            <Badge 
              :variant="post.status === 'published' ? 'default' : 'secondary'"
              :class="{
                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': post.status === 'published',
                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': post.status === 'draft',
              }"
            >
              {{ post.status }}
            </Badge>
          </TableCell>
          <TableCell class="text-right">
            <div class="flex justify-end gap-2">
              <Button 
                variant="ghost" 
                size="sm"
                @click="emit('edit', post.id)"
              >
                <Pencil class="w-4 h-4" />
                <span class="sr-only">Edit</span>
              </Button>
              <Button 
                variant="ghost" 
                size="sm"
                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                @click="openDeleteDialog(post.id)"
              >
                <Trash class="w-4 h-4" />
                <span class="sr-only">Delete</span>
              </Button>
            </div>
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>
    <!-- Delete Confirmation Dialog -->
    <Dialog v-model:open="showDeleteDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Confirm Deletion</DialogTitle>
          <DialogDescription>
            Are you sure you want to delete this post? This action cannot be undone.
          </DialogDescription>
        </DialogHeader>
        <DialogFooter>
          <Button variant="outline" @click="showDeleteDialog = false">
            Cancel
          </Button>
          <Button variant="destructive" @click="confirmDelete">
            Delete
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </div>
</template>