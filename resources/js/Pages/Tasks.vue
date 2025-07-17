<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'

import Card from '@/Components/Card.vue'
import TaskItem from '@/Components/TaskItem.vue'

const page = usePage()
const user = page.props.user // <-- Get user from props

const tasks = ref([])
const newTask = ref('')
const selectedTaskIds = ref([])
const error = ref('')

// Filtering state
const filterTitle = ref('')
const filterCompleted = ref('all') // 'all', 'completed', 'incomplete'

// Pagination state
const currentPage = ref(1)
const pageSize = 5

// Load tasks from API
const loadTasks = async () => {
  try {
    const response = await axios.get('/api/tasks')
    tasks.value = response.data
    error.value = ''
  } catch (err) {
    error.value = 'Failed to load tasks.'
  }
}

// Add new task
const addTask = async () => {
  if (!user) {
    error.value = 'You must be logged in to add a task.'
    return
  }
  if (!newTask.value.trim()) {
    error.value = 'Task title cannot be empty.'
    return
  }
  try {
    const response = await axios.post('/api/tasks', { title: newTask.value })
    tasks.value.push(response.data)
    newTask.value = ''
    error.value = ''
  } catch (err) {
    error.value = 'Failed to add task.'
  }
}

// Update task
const updateTask = async (updated) => {
  try {
    const response = await axios.put(`/api/tasks/${updated.id}`, updated)
    const index = tasks.value.findIndex(t => t.id === updated.id)
    if (index !== -1) tasks.value[index] = response.data
    error.value = ''
  } catch (err) {
    error.value = 'Failed to update task.'
  }
}

// Delete task
const deleteTask = async (id) => {
  try {
    await axios.delete(`/api/tasks/${id}`)
    tasks.value = tasks.value.filter(t => t.id !== id)
    error.value = ''
  } catch (err) {
    error.value = 'Failed to delete task.'
  }
}

// Handle selection
const handleSelect = (id, checked) => {
  if (checked) {
    if (!selectedTaskIds.value.includes(id)) selectedTaskIds.value.push(id)
  } else {
    selectedTaskIds.value = selectedTaskIds.value.filter(tid => tid !== id)
  }
}

// Bulk delete
const deleteSelectedTasks = async () => {
  try {
    for (const id of selectedTaskIds.value) {
      await deleteTask(id)
    }
    selectedTaskIds.value = []
    error.value = ''
  } catch (err) {
    error.value = 'Failed to delete selected tasks.'
  }
}

// Filtered tasks
const filteredTasks = computed(() => {
  let filtered = tasks.value
  if (filterTitle.value.trim()) {
    filtered = filtered.filter(t =>
      t.title.toLowerCase().includes(filterTitle.value.trim().toLowerCase())
    )
  }
  if (filterCompleted.value === 'completed') {
    filtered = filtered.filter(t => t.completed)
  } else if (filterCompleted.value === 'incomplete') {
    filtered = filtered.filter(t => !t.completed)
  }
  return filtered
})

// Pagination logic
const totalPages = computed(() =>
  Math.ceil(filteredTasks.value.length / pageSize)
)
const paginatedTasks = computed(() => {
  const start = (currentPage.value - 1) * pageSize
  return filteredTasks.value.slice(start, start + pageSize)
})

const totalTasks = computed(() => tasks.value.length)

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

onMounted(loadTasks)
watch(tasks, () => console.log('Tasks changed:', tasks.value))
watch([filterTitle, filterCompleted], () => { currentPage.value = 1 }) // Reset page on filter

const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
</script>

<template>
  <nav class="bg-gray-800 text-white px-4 py-3 flex justify-between items-center mb-6 rounded">
    <div>
      <a href="/" class="font-bold text-lg">MyApp</a>
      <a href="/dashboard" class="ml-4 hover:underline">Dashboard</a>
      <a href="/tasks" class="ml-4 hover:underline">Tasks</a>
    </div>
    <div>
      <template v-if="user">
        <span class="mr-2">Hello, {{ user.name }}</span>
        <form method="POST" action="/logout" class="inline">
          <input type="hidden" name="_token" :value="csrf" />
          <button
            type="submit"
            class="hover:underline bg-transparent border-0 text-white cursor-pointer"
            style="padding:0"
          >Logout</button>
        </form>
      </template>
      <template v-else>
        <a href="/login" class="hover:underline mr-2">Login</a>
        <a href="/register" class="hover:underline">Register</a>
      </template>
    </div>
  </nav>

  <div class="max-w-2xl mx-auto px-4 py-6">
    <div class="bg-white shadow rounded-lg p-6">
      <h1 class="text-xl font-bold text-blue-600 mb-4">Vue 3 Task Manager</h1>

      <!-- Error message -->
      <div v-if="error" class="mb-2 text-red-600 bg-red-50 border border-red-200 rounded px-3 py-2">
        {{ error }}
      </div>

      <!-- Filters -->
      <div class="flex gap-2 mb-4">
        <input
          v-model="filterTitle"
          placeholder="Filter by title"
          class="flex-1 border border-gray-300 rounded px-3 py-2"
        />
        <select v-model="filterCompleted" class="border border-gray-300 rounded px-2 py-2">
          <option value="all">All</option>
          <option value="completed">Completed</option>
          <option value="incomplete">Incomplete</option>
        </select>
      </div>

      <div class="flex gap-2 mb-4" v-if="user">
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
      <div v-else class="mb-4 text-gray-500">
        Please <a href="/login" class="text-blue-600 underline">login</a> to add tasks.
      </div>

      <p class="text-sm text-gray-500 mb-2">Total Tasks: {{ totalTasks }}</p>

      <div class="mb-2 flex gap-2">
        <button
          @click="deleteSelectedTasks"
          :disabled="selectedTaskIds.length === 0"
          class="bg-red-600 text-white px-3 py-1 rounded disabled:opacity-50"
        >
          Delete Selected
        </button>
      </div>

      <ul class="space-y-2">
        <TaskItem
          v-for="task in paginatedTasks"
          :key="task.id"
          :task="task"
          :selected="selectedTaskIds.includes(task.id)"
          :user="user"
          @update="updateTask"
          @delete="deleteTask"
          @select="handleSelect"
        />
      </ul>

      <!-- Pagination controls -->
      <div class="flex justify-center items-center gap-2 mt-4" v-if="totalPages > 1">
        <button
          @click="goToPage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="px-2 py-1 rounded border bg-gray-100 disabled:opacity-50"
        >Prev</button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button
          @click="goToPage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="px-2 py-1 rounded border bg-gray-100 disabled:opacity-50"
        >Next</button>
      </div>
    </div>
  </div>

  <meta name="csrf-token" content="{{ csrf_token() }}">
</template>
