<script setup lang="ts">
import { Button } from '@/components/ui/button';

defineProps<{
  loading: boolean
}>()

const emit = defineEmits(['reset', 'submit'])
</script>

<template>
  <div class="flex justify-end gap-2 pt-4">
    <Button 
      type="button" 
      variant="outline" 
      @click="emit('reset')"
    >
      Reset
    </Button>
    <Button 
      type="submit" 
      @click="emit('submit')"
      :disabled="loading"
    >
      <span v-if="loading">Creating...</span>
      <span v-else>Create Post</span>
    </Button>
  </div>
</template>