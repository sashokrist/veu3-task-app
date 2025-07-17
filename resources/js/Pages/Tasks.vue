<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import axios from 'axios'

import Card from '@/Components/Card.vue'
import TaskItem from '@/Components/TaskItem.vue'

const tasks = ref([])
const newTask = ref('')

// Load tasks from API
const loadTasks = async () => {
  try {
    const response = await axios.get('/api/tasks')
    tasks.value = response.data
  } catch (error) {
    console.error('Failed to load tasks:', error)
  }
}

// Add new task
const addTask = async () => {
  if (!newTask.value.trim()) return

  try {
    const response = await axios.post('/api/tasks', { title: newTask.value })
    tasks.value.push(response.data)
    newTask.value = ''
  } catch (error) {
    console.error('Failed to add task:', error)
  }
}

// Update task
const updateTask = async (updated) => {
  try {
    const response = await axios.put(`/api/tasks/${updated.id}`, updated)
    const index = tasks.value.findIndex(t => t.id === updated.id)
    if (index !== -1) tasks.value[index] = response.data
  } catch (error) {
    console.error('Failed to update task:', error)
  }
}

// Delete task
const deleteTask = async (id) => {
  try {
    await axios.delete(`/api/tasks/${id}`)
    tasks.value = tasks.value.filter(t => t.id !== id)
  } catch (error) {
    console.error('Failed to delete task:', error)
  }
}

// Computed
const totalTasks = computed(() => tasks.value.length)

// Load tasks when component is mounted
onMounted(loadTasks)

// Optional: watch to debug
watch(tasks, () => console.log('Tasks changed:', tasks.value))
</script>

<template>
  <div class="max-w-2xl mx-auto px-4 py-6">
    <div class="bg-white shadow rounded-lg p-6">
      <h1 class="text-xl font-bold text-blue-600 mb-4">Vue 3 Task Manager</h1>

      <div class="flex gap-2 mb-4">
        <input
          v-model="newTask"
          placeholder="New Task"
          class="flex-1 border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          @keyup.enter="addTask"
        />
        <button
          @click="addTask"
          class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded"
        >
          Add
        </button>
      </div>

      <p class="text-sm text-gray-500 mb-2">Total Tasks: {{ totalTasks }}</p>

      <ul class="space-y-2">
        <TaskItem
          v-for="task in tasks"
          :key="task.id"
          :task="task"
          @update="updateTask"
          @delete="deleteTask"
        />
      </ul>
    </div>
  </div>
</template>
