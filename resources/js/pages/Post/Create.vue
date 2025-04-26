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
import { useToast } from 'vue-toastification';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Posts', href: '/posts' },
  { title: 'Create', href: '/posts/create' }
]

const userId = usePage().props.userId;
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
  departments: Array<{
    id: number
    name: string
  }>
}>()

const toast = useToast()

// Form state
const form = ref({
  title: '',
  content: '',
  category: '',
  user_id: userId,
  department_id: null as number | null,
  document: null as File | null,
  link: '',
  images: [] as File[]
})

const loading = ref(false)

const handleFileChange = (e: Event, field: string) => {
  const target = e.target as HTMLInputElement
  if (target.files) {
    form.value[field] = field === 'images' 
      ? Array.from(target.files) 
      : target.files[0]
  }
}

const handleSubmit = async () => {
  if (!form.value.title || !form.value.content || !form.value.category) {
    toast.error('Please fill all required fields')
    return
  }

  const formData = new FormData()
  formData.append('title', form.value.title)
  formData.append('content', form.value.content)
  formData.append('category', form.value.category)
  formData.append('user_id', form.value.user_id.toString())

  if (form.value.department_id) {
    formData.append('department_id', form.value.department_id.toString())
  }
  if (form.value.link) {
    formData.append('link', form.value.link)
  }
  if (form.value.document) {
    formData.append('document', form.value.document)
  }
  if (form.value.images.length > 0) {
    form.value.images.forEach(file => {
      formData.append('images[]', file)
    })
  }

  loading.value = true

  try {
    await axios.post('/api/posts', formData)
    toast.success('Post created successfully')
    resetForm()
  } catch (error: any) {
    if (error.response && error.response.data && error.response.data.errors) {
      Object.values(error.response.data.errors).forEach((msg: any) => {
        toast.error(msg as string)
      })
    } else {
      toast.error('Something went wrong')
    }
  } finally {
    loading.value = false
  }
}

const resetForm = () => {
  form.value = {
    title: '',
    content: '',
    category: '',
    department_id: null,
    document: null,
    link: '',
    images: []
  }
}
</script>

<template>
  <Head title="Create Post" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-6">
        <h2 class="text-xl font-semibold mb-6">Create New Post</h2>
        
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <Label for="title">Title *</Label>
            <Input id="title" v-model="form.title" required />
          </div>

          <div>
            <Label for="content">Content *</Label>
            <Textarea id="content" v-model="form.content" rows="5" required />
          </div>

          <div>
            <Label for="category">Category *</Label>
            <Input id="category" v-model="form.category" required />
          </div>

          <div>
            <Label for="department">Department</Label>
            <select 
              id="department" 
              v-model="form.department_id"
              class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
            >
              <option :value="null">Select Department</option>
              <option 
                v-for="department in departments" 
                :key="department.id" 
                :value="department.id"
              >
                {{ department.name }}
              </option>
            </select>
          </div>

          <div>
            <Label for="link">Link</Label>
            <Input id="link" v-model="form.link" type="url" />
          </div>

          <div>
            <Label for="document">Document</Label>
            <Input 
              id="document" 
              type="file" 
              @change="e => handleFileChange(e, 'document')"
              accept=".pdf,.doc,.docx"
            />
          </div>

          <div>
            <Label for="images">Images (Multiple)</Label>
            <Input 
              id="images" 
              type="file" 
              multiple 
              @change="e => handleFileChange(e, 'images')"
              accept="image/*"
            />
          </div>

          <div class="flex justify-end gap-2 pt-4">
            <Button 
              type="button" 
              variant="outline" 
              @click="resetForm"
            >
              Reset
            </Button>
            <Button type="submit" :disabled="loading">
              <span v-if="loading">Creating...</span>
              <span v-else>Create Post</span>
            </Button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>