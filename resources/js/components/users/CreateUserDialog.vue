<template>
    <Dialog v-model:open="isOpen">
      <DialogTrigger as-child>
        <Button @click="openDialog">
          <UserRoundPlus class="w-4 h-4 mr-2" /> Create
        </Button>
      </DialogTrigger>
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Create New User</DialogTitle>
          <DialogDescription>Fill in the details to add a new user.</DialogDescription>
        </DialogHeader>
  
        <form @submit.prevent="createUser" class="grid gap-4">
          <Input v-model="formData.name" placeholder="Name" required />
          <Input v-model="formData.email" type="email" placeholder="Email" required />
          <Input v-model="formData.password" type="password" placeholder="Password" required />
  
          <!-- Role Select -->
          <Select v-model="formData.role">
            <SelectTrigger>
              <SelectValue placeholder="Select Role" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="admin">Admin</SelectItem>
              <SelectItem value="user">User</SelectItem>
            </SelectContent>
          </Select>
  
          <!-- Status Select -->
          <Select v-model="formData.status">
            <SelectTrigger>
              <SelectValue placeholder="Select Status" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="active">Active</SelectItem>
              <SelectItem value="inactive">Inactive</SelectItem>
            </SelectContent>
          </Select>
  
          <!-- Department Select -->
          <Select v-model="formData.department_id">
            <SelectTrigger>
              <SelectValue placeholder="Select Department" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem :value="null">No Department</SelectItem>
              <SelectItem
                v-for="department in departments"
                :key="department.id"
                :value="department.id"
              >
                {{ department.name }}
              </SelectItem>
            </SelectContent>
          </Select>
  
          <DialogFooter>
            <Button variant="outline" type="button" @click="closeDialog">Cancel</Button>
            <Button type="submit" :disabled="loading">
              <span v-if="loading">Creating...</span>
              <span v-else>Create</span>
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>
  </template>

<script setup lang="ts">
import { ref } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";

import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger
} from "@/components/ui/dialog";

import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue
} from "@/components/ui/select";

import { Input } from "@/components/ui/input";
import { UserRoundPlus } from "lucide-vue-next";

const props = defineProps<{
  departments: Array<{ id: number; name: string }>
}>();

const toast = useToast();
const isOpen = ref(false);
const loading = ref(false);
const formData = ref({
  name: "",
  email: "",
  password: "",
  role: "user",
  status: "active",
  department_id: null,
});

const openDialog = () => (isOpen.value = true);
const closeDialog = () => (isOpen.value = false);

const createUser = async () => {
  loading.value = true;
  try {
    await axios.post("/api/users", formData.value);
    toast.success("User created successfully!");
    setTimeout(() => location.reload(), 2000);
  } catch (error) {
    toast.error("Failed to create user");
    console.error(error);
  } finally {
    loading.value = false;
    closeDialog();
  }
};
</script>
