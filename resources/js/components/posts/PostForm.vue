<script setup lang="ts">
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Label } from '@/components/ui/label'
import { useToast } from 'vue-toastification'
import axios from 'axios'

const props = defineProps<{
  departments: Array<{
    id: number
    name: string
  }>
}>()

const emit = defineEmits(['success'])

const form = ref({
  title: '',
  content: '',
  category: '',
  department_id: null,
  document: null,
  link: '',
  images: [] as File[]
})

const loading = ref(false)
const toast = useToast()

const handleSubmit = async () => {
  if (!form.value.title || !form.value.content || !form.value.category) {
    toast.error('Please fill all required fields')
    return
  }

  loading.value = true
  
  try {
    const formData = new FormData()
    
    // Append all fields
    formData.append('title', form.value.title)
    formData.append('content', form.value.content)
    formData.append('category', form.value.category)
    
    if (form.value.department_id) {
      formData.append('department_id', form.value.department_id)
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

    const response = await axios.post('/api/posts', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'Accept': 'application/json'
      }
    })

    toast.success(response.data.message)
    emit('success', response.data.post)
    
    // Reset form
    form.value = {
      title: '',
      content: '',
      category: '',
      department_id: null,
      document: null,
      link: '',
      images: []
    }
  } catch (error: any) {
    if (error.response?.data?.errors) {
      Object.values(error.response.data.errors).flat().forEach(msg => {
        toast.error(msg)
      })
    } else {
      toast.error(error.response?.data?.message || 'Failed to create post')
    }
    console.error('Error:', error)
  } finally {
    loading.value = false
  }
}

const handleFileChange = (e: Event, field: string) => {
  const target = e.target as HTMLInputElement
  if (target.files) {
    form.value[field] = field === 'images' 
      ? Array.from(target.files) 
      : target.files[0]
  }
}
</script>

<template>
  <form @submit.prevent="handleSubmit" class="space-y-4 max-h-screen overflow-y-auto p-4">
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

    <Button type="submit" class="mt-4" :disabled="loading">
      <span v-if="loading">Creating...</span>
      <span v-else>Create Post</span>
    </Button>
  </form>
</template>