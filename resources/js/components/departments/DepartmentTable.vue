<script setup lang="ts">
import { ref } from 'vue'
import { Trash, Loader2 } from 'lucide-vue-next'
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import { useToast } from 'vue-toastification'
import { router } from '@inertiajs/vue3'

const toast = useToast()

const props = defineProps<{
  departments: Array<{
    id: number
    name: string
    description: string
    logo: string | null
    image: string | null
    social: {
      facebook?: string
      twitter?: string
      instagram?: string
    }
  }>
}>()

const emit = defineEmits(['refresh'])

// Track the currently selected department for deletion
const departmentToDelete = ref<number | null>(null)
const isDeleting = ref(false)
const dialogOpen = ref(false)

function truncate(str: string, length: number) {
  return str.length > length ? str.slice(0, length) + '...' : str
}

const handleDelete = async (id: number) => {
  isDeleting.value = true
  try {
    await axios.delete(`api/departments/${id}`)
    toast.success('Department deleted successfully')
    dialogOpen.value = false
    emit('refresh') // Emit event to parent to refresh data
  } catch (error) {
    toast.error('Failed to delete department')
    console.error('Delete error:', error)
  } finally {
    isDeleting.value = false
    departmentToDelete.value = null
  }
}

const openDeleteDialog = (id: number) => {
  departmentToDelete.value = id
  dialogOpen.value = true
}
</script>

<template>
  <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-800">
          <tr>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Logo</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Name</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Description</th>
            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="dept in props.departments" :key="dept.id" class="hover:bg-muted/50">
                <td class="px-4 py-2">
                    <img v-if="dept.logo" :src="`/storage/${dept.logo}`" class="h-10 w-10 object-cover" />
                </td>
                <td class="px-4 py-2 whitespace-nowrap">{{ dept.name }}</td>
                <td class="px-4 py-2">{{ truncate(dept.description, 200) }}</td>
                <td class="text-right py-2 pr-4">
                    <Dialog>
                    <DialogTrigger as-child>
                        <Button variant="ghost" size="sm" class="text-red-500 hover:text-red-700">
                        <Trash class="w-4 h-4" />
                        </Button>
                    </DialogTrigger>
                    <DialogContent>
                        <DialogHeader>
                        <DialogTitle>Are you sure?</DialogTitle>
                        <DialogDescription>
                            This will permanently delete 
                            <span class="font-semibold">{{ dept.name }}</span>.
                        </DialogDescription>
                        </DialogHeader>
                        <DialogFooter>
                        <DialogClose as-child>
                            <Button variant="outline">Cancel</Button>
                        </DialogClose>
                        <Button
                            variant="destructive"
                            @click="handleDelete(dept.id)"
                            :disabled="isDeleting"
                        >
                            <Loader2 v-if="isDeleting" class="mr-2 h-4 w-4 animate-spin" />
                            <span>{{ isDeleting ? 'Deleting...' : 'Delete' }}</span>
                        </Button>
                        </DialogFooter>
                    </DialogContent>
                    </Dialog>
                </td>
            </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>