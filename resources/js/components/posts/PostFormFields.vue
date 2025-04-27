<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';

const editor = ClassicEditor;
const editorConfig = {
  toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'],
};

defineProps<{
  form: {
    title: string
    content: string
    category: string
    department_id: number | null
    link: string
  }
  departments: Array<{
    id: number
    name: string
  }>
}>()

const emit = defineEmits(['update:form', 'fileChange'])

const handleFileChange = (e: Event, field: string) => {
  emit('fileChange', e, field)
}

const categories = [
  { value: 'Announcement', label: 'Announcement' },
  { value: 'News', label: 'News' },
];
</script>

<template>
  <div>
    <div class="mt-4">
      <Label for="category">Category *</Label>
      <Select
        :modelValue="form.category"
        @update:modelValue="value => emit('update:form', { ...form, category: value })"
      >
        <SelectTrigger class="w-full">
          <SelectValue placeholder="Select a category" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem 
            v-for="category in categories" 
            :key="category.value"
            :value="category.value"
          >
            {{ category.label }}
          </SelectItem>
        </SelectContent>
      </Select>
    </div>

    <div class="mt-4">
      <Label for="title">Title *</Label>
      <Input 
        id="title" 
        :modelValue="form.title" 
        @update:modelValue="value => emit('update:form', { ...form, title: value })" 
        required 
      />
    </div>

    <!-- Content Input with CKEditor -->
    <div class="mt-4">
        <Label for="title">Content *</Label>
        <InputLabel for="content" value="Content" />
        <ckeditor :editor="editor" v-model="form.content" :config="editorConfig"></ckeditor>
    </div>

    <div class="mt-4">
      <Label for="link">Link</Label>
      <Input 
        id="link" 
        type="url" 
        :modelValue="form.link" 
        @update:modelValue="value => emit('update:form', { ...form, link: value })" 
      />
    </div>

    <div class="mt-4">
      <Label for="document">Document</Label>
      <Input 
        id="document" 
        type="file" 
        @change="e => handleFileChange(e, 'document')"
        accept=".pdf,.doc,.docx"
      />
    </div>

    <div class="mt-4">
      <Label for="images">Images (Multiple)</Label>
      <Input 
        id="images" 
        type="file" 
        multiple 
        @change="e => handleFileChange(e, 'images')"
        accept="image/*"
      />
    </div>
  </div>
</template>