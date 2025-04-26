<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useToast } from 'vue-toastification'
import { Button } from '@/components/ui/button'
import { router } from '@inertiajs/vue3'
import DepartmentTable from '@/components/departments/DepartmentTable.vue'

const toast = useToast()

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Departments', href: '/departments' },
]

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

const create = () => {
  router.visit(route('departments.create'))
}

const refreshDepartments = () => {
  router.reload({ only: ['departments'] })
}
</script>

<template>
  <Head title="Departments" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="flex justify-end items-center">
        <Button @click="create()">Create</Button>
      </div>
      
      <DepartmentTable 
        :departments="props.departments"
        @refresh="refreshDepartments"
      />
    </div>
  </AppLayout>
</template>