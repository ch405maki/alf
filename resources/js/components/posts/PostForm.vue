<script setup lang="ts">
import { ref } from 'vue';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import PostFormFields from '@/components/posts/PostFormFields.vue';
import PostFormActions from '@/components/posts/PostFormActions.vue';
import { router } from '@inertiajs/vue3'; 

const props = defineProps<{
  userId: number | string
  userDepartmentId: number | string
  departments: {
    id: number
    name: string
  }[]
}>()

interface PostForm {
  title: string
  content: string
  category: string
  user_id: number | string
  department_id: number | null
  document: File | null
  link: string
  images: File[]
}

const emit = defineEmits(['success'])

const toast = useToast()

const form = ref<PostForm>({
  title: '',
  content: '',
  category: '',
  user_id: props.userId,
  department_id: Number(props.userDepartmentId),
  document: null,
  link: '',
  images: []
})

const loading = ref(false)

const handleFileChange = (e: Event, field: 'document' | 'images') => {
  const target = e.target as HTMLInputElement
  if (!target.files) return
  
  if (field === 'images') {
    form.value.images = Array.from(target.files)
  } else {
    form.value.document = target.files[0]
  }
}

const validateForm = (): boolean => {
  if (!form.value.title.trim()) {
    toast.error('Title is required')
    return false
  }
  if (!form.value.content.trim()) {
    toast.error('Content is required')
    return false
  }
  if (!form.value.category) {
    toast.error('Please select a category')
    return false
  }
  return true
}

const handleSubmit = async () => {
  if (!validateForm()) return

  const formData = new FormData()
  formData.append('title', form.value.title)
  formData.append('content', form.value.content)
  formData.append('category', form.value.category)
  formData.append('user_id', form.value.user_id.toString())

  if (form.value.department_id !== null) {
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
    await axios.post('/api/posts', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    toast.success('Post created successfully')
    resetForm()
    emit('success')
    
    router.visit('/posts')

  } catch (error: unknown) {
    if (axios.isAxiosError(error)) {
      if (error.response?.data?.errors) {
        Object.values(error.response.data.errors).forEach((msg: any) => {
          toast.error(Array.isArray(msg) ? msg.join(' ') : msg)
        })
      } else {
        toast.error(error.message || 'Something went wrong')
      }
    } else {
      toast.error('An unexpected error occurred')
      console.error(error)
    }
  } finally {
    loading.value = false
  }
}

const resetForm = () => {
  form.value = {
    ...form.value,
    title: '',
    content: '',
    category: '',
    document: null,
    link: '',
    images: []
  }
  // Reset file inputs
  const fileInputs = document.querySelectorAll('input[type="file"]')
  fileInputs.forEach(input => (input as HTMLInputElement).value = '')
}
</script>

<template>
  <form @submit.prevent="handleSubmit" class="space-y-6">
    <PostFormFields 
      :form="form" 
      :departments="props.departments" 
      @update:form="newValue => form = newValue"
      @fileChange="handleFileChange"
    />
    
    <PostFormActions 
      :loading="loading" 
      @reset="resetForm" 
      @submit="handleSubmit"
      class="mt-6"
    />
  </form>
</template>