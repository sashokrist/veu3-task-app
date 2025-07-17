<template>
  <li class="flex items-center justify-between border border-gray-200 rounded px-4 py-2 bg-gray-50">
    <div v-if="isEditing" class="flex-1 mr-4">
      <input v-model="editableTask.title" class="border px-2 py-1 w-full rounded" />
    </div>
    <div v-else class="flex-1 mr-4">
      <span :class="{ 'line-through text-gray-400': task.completed }">{{ task.title }}</span>
    </div>

    <div class="flex gap-2">
      <button
        v-if="isEditing"
        @click="saveTask"
        class="bg-green-600 text-white text-sm px-3 py-1 rounded hover:bg-green-700"
      >
        Save
      </button>

      <button
        v-else
        @click="startEdit"
        class="bg-yellow-500 text-white text-sm px-3 py-1 rounded hover:bg-yellow-600"
      >
        Edit
      </button>

      <button
        @click="$emit('delete', task.id)"
        class="bg-red-600 text-white text-sm px-3 py-1 rounded hover:bg-red-700"
      >
        Delete
      </button>
    </div>
  </li>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({ task: Object })
const emit = defineEmits(['update', 'delete'])

const isEditing = ref(false)
const editableTask = ref({ ...props.task })

const startEdit = () => {
  isEditing.value = true
  editableTask.value = { ...props.task }
}

const saveTask = () => {
  emit('update', editableTask.value)
  isEditing.value = false
}
</script>
