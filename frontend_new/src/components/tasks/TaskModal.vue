<template>
    <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
        <h2 class="text-2xl font-bold mb-4 text-center">
          {{ task.id ? 'Edit Task' : 'Create Task' }}
        </h2>
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <!-- Title -->
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
            <input
              type="text"
              id="title"
              v-model="title"
              required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
            <span v-if="errors.title" class="text-red-500 text-sm">{{ errors.title[0] }}</span>
          </div>
  
          <!-- Description -->
          <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
            <textarea
              id="description"
              v-model="description"
              required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            ></textarea>
            <span v-if="errors.description" class="text-red-500 text-sm">{{ errors.description[0] }}</span>
          </div>
  
          <!-- Due Date -->
          <div>
            <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date:</label>
            <input
              type="date"
              id="due_date"
              v-model="due_date"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
            <span v-if="errors.due_date" class="text-red-500 text-sm">{{ errors.due_date[0] }}</span>
          </div>
  
          <!-- Priority -->
          <div>
            <label for="priority" class="block text-sm font-medium text-gray-700">Priority:</label>
            <select
              id="priority"
              v-model="priority"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            >
              <option value="">Select Priority</option>
              <option value="Urgent">Urgent</option>
              <option value="High">High</option>
              <option value="Normal">Normal</option>
              <option value="Low">Low</option>
            </select>
            <span v-if="errors.priority" class="text-red-500 text-sm">{{ errors.priority[0] }}</span>
          </div>
  
          <!-- Tags -->
          <div>
            <label for="tags" class="block text-sm font-medium text-gray-700">Tags:</label>
            <input
              type="text"
              id="tags"
              v-model="tagInput"
              @keydown.enter.prevent="addTag"
              placeholder="Press Enter to add tags"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
            <div class="mt-2 flex flex-wrap gap-2">
              <span
                v-for="(tag, index) in tags"
                :key="index"
                class="inline-flex items-center bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm"
              >
                {{ tag }}
                <button
                  type="button"
                  @click="removeTag(index)"
                  class="ml-2 text-red-500 hover:text-red-700"
                >
                  x
                </button>
              </span>
            </div>
            <span v-if="errors.tags" class="text-red-500 text-sm">{{ errors.tags[0] }}</span>
          </div>
  
          <!-- Attachments -->
          <div>
            <label for="attachments" class="block text-sm font-medium text-gray-700">Attachments:</label>
            <!-- Display existing attachments -->
            <div v-if="existingAttachments.length > 0" class="mt-2 flex flex-wrap gap-2">
              <span
                v-for="(file, index) in existingAttachments"
                :key="index"
                class="inline-flex items-center bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-sm"
              >
                <a :href="file.url" target="_blank">Download{{ file.name }}</a>
                <button
                  type="button"
                  @click="removeExistingFile(index)"
                  class="ml-2 text-red-500 hover:text-red-700"
                >
                  x
                </button>
              </span>
            </div>
            <!-- File input for new attachments -->
            <input
              type="file"
              id="attachments"
              multiple
              @change="handleFiles"
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            />
            <div class="mt-2 flex flex-wrap gap-2">
              <span
                v-for="(file, index) in attachments"
                :key="index"
                class="inline-flex items-center bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-sm"
              >
                {{ file.name }}
                <button
                  type="button"
                  @click="removeFile(index)"
                  class="ml-2 text-red-500 hover:text-red-700"
                >
                  x
                </button>
              </span>
            </div>
            <span v-if="errors.attachments" class="text-red-500 text-sm">{{ errors.attachments[0] }}</span>
          </div>
  
          <!-- Modal Actions -->
          <div class="flex justify-end gap-4 mt-6">
            <button
              type="submit"
              class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700"
            >
              {{ task.id ? 'Update' : 'Create' }}
            </button>
            <button
              type="button"
              @click="$emit('close')"
              class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      task: {
        type: Object,
        default: () => ({}),
      },
    },
    data() {
      return {
        title: '',
        description: '',
        due_date: '',
        priority: '',
        tagInput: '',
        tags: [],
        attachments: [],
        existingAttachments: [], // Holds already uploaded attachments
        errors: {},
      };
    },
    watch: {
      task: {
        immediate: true,
        handler(newTask) {
          if (newTask && newTask.id) {
            this.title = newTask.title;
            this.description = newTask.description;
            if (newTask.due_date) {
                const parsedDate = new Date(newTask.due_date);
                this.due_date = parsedDate.toISOString().split('T')[0];
            } else {
                this.due_date = '';
            }
            this.priority = newTask.priority;
            this.tags = [...newTask.tags];
            this.existingAttachments = newTask.attachments || []; 
          } else {
            this.resetFields();
          }
        },
      },
    },
    methods: {
      resetFields() {
        this.title = '';
        this.description = '';
        this.due_date = '';
        this.priority = '';
        this.tags = [];
        this.attachments = [];
        this.existingAttachments = [];
        this.errors = {};
      },
      addTag() {
        if (this.tagInput.trim()) {
          this.tags.push(this.tagInput.trim());
          this.tagInput = '';
        }
      },
      removeTag(index) {
        this.tags.splice(index, 1);
      },
      handleFiles(event) {
        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
          this.attachments.push(files[i]);
        }
      },
      removeFile(index) {
        this.attachments.splice(index, 1);
      },
      removeExistingFile(index) {
        this.existingAttachments.splice(index, 1); // Remove existing attachment
      },
      async handleSubmit() {
        const formData = new FormData();
        formData.append('title', this.title);
        formData.append('description', this.description);
        if (this.due_date) formData.append('due_date', this.due_date);
        if (this.priority) formData.append('priority', this.priority);
        this.tags.forEach((tag) => formData.append('tags[]', tag));
        this.attachments.forEach((file) => formData.append('attachments[]', file));
        this.existingAttachments.forEach((file) => formData.append('existing_attachments[]', file.id)); // Include existing attachments
  
        try {
          const headers = {
            Authorization: `Bearer ${this.$store.state.auth.token}`,
            'Content-Type': 'multipart/form-data',
          };
  
          if (this.task.id) {
            formData.append('_method', 'PUT');
            await this.$api.post(`/tasks/${this.task.id}`, formData, { headers });
          } else {
            await this.$api.post('/tasks', formData, { headers });
          }
  
          this.$emit('close');
          this.$emit('update');
          this.$emit('success', 'Task saved successfully!');
        } catch (error) {
          if (error.response?.status === 422) {
            this.errors = error.response.data.errors;
          } else {
            alert('An error occurred.');
          }
        }
      },
    },
  };
  </script>
  
  <style scoped>
  /* Add styles if needed */
  </style>
  