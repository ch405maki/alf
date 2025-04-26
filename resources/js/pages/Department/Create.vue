<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import axios from 'axios'
import { useToast } from 'vue-toastification'
import { Input } from '@/components/ui/input'
import { Textarea } from '@/components/ui/textarea'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'

const toast = useToast()

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Departments', href: '/departments' },
  { title: 'Create', href: '/departments/create' },
]

// form state
const name = ref('')
const description = ref('')
const logo = ref<File | null>(null)
const image = ref<File | null>(null)
const social = ref({
  facebook: '',
  twitter: '',
  instagram: '',
})

// file input handlers
const handleLogoChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files?.length) {
    logo.value = target.files[0]
  }
}

const handleImageChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files?.length) {
    image.value = target.files[0]
  }
}

const handleSubmit = async () => {
  try {
    const formData = new FormData()
    formData.append('name', name.value)
    formData.append('description', description.value)

    if (logo.value) {
      formData.append('logo', logo.value)
    }

    if (image.value) {
      formData.append('image', image.value)
    }

    formData.append('social[facebook]', social.value.facebook)
    formData.append('social[twitter]', social.value.twitter)
    formData.append('social[instagram]', social.value.instagram)

    const res = await axios.post('/api/departments', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    })

    toast.success('Department created successfully!')
    // Reset form
    name.value = ''
    description.value = ''
    logo.value = null
    image.value = null
    social.value = { facebook: '', twitter: '', instagram: '' }

  } catch (error: any) {
    console.error('Error:', error.response?.data || error.message)
    toast.error('Failed to save department.')
  }
}
</script>

<template>
  <Head title="Departments" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-6">
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <div class="space-y-2">
            <Label for="name">Name</Label>
            <Input id="name" v-model="name" type="text" />
          </div>

          <div class="space-y-2">
            <Label for="description">Description</Label>
            <Textarea id="description" v-model="description" rows="3" />
          </div>

          <div class="space-y-2">
            <Label for="logo">Logo</Label>
            <Input id="logo" type="file" @change="handleLogoChange" />
          </div>

          <div class="space-y-2">
            <Label for="image">Image</Label>
            <Input id="image" type="file" @change="handleImageChange" />
          </div>

          <div class="space-y-2">
            <Label>Social (optional)</Label>
            <Input v-model="social.facebook" type="text" placeholder="Facebook URL" />
            <Input v-model="social.twitter" type="text" placeholder="Twitter URL" />
            <Input v-model="social.instagram" type="text" placeholder="Instagram URL" />
          </div>

          <Button type="submit">Save Department</Button>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
