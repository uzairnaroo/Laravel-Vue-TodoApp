<template>
    <div class="max-w-4xl mx-auto mt-12 bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-3xl font-bold text-blue-600 mb-4">{{ task.title }}</h2>
        <p class="text-gray-700 text-lg mb-4">{{ task.description }}</p>

        <div class="flex flex-wrap items-center mb-4">
            <p class="text-sm font-semibold text-gray-500 mr-4">
                <span class="font-bold">Priority:</span> {{ task.priority }}
            </p>
            <p class="text-sm font-semibold text-gray-500 mr-4">
                <span class="font-bold">Due Date:</span> {{ task.due_date }}
            </p>
            <p class="text-sm font-semibold">
                <span class="font-bold">Status:</span>
                <span
                    :class="task.is_completed ? 'text-green-600' : 'text-red-600'"
                >
                    {{ task.is_completed ? 'Completed' : 'Todo' }}
                </span>
            </p>
        </div>

        <!-- Tags Section -->
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Tags:</h3>
            <div class="flex flex-wrap gap-2">
                <span
                    v-for="tag in task.tags"
                    :key="tag"
                    class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full"
                >
                    {{ tag }}
                </span>
            </div>
        </div>

        <!-- Attachments Section -->
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Attachments:</h3>
            <ul class="list-disc list-inside text-blue-600">
                <li
                    v-for="attachment in task.attachments"
                    :key="attachment.id"
                >
                    <a
                        :href="attachment.url"
                        target="_blank"
                        class="hover:underline"
                    >
                        Download Attachment
                    </a>
                </li>
            </ul>
        </div>

        <!-- Actions Section -->
        <div class="flex gap-4 mt-6">
            <button
                @click="toggleCompletion"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
            >
                {{ task.is_completed ? 'Mark as Incomplete' : 'Mark as Complete' }}
            </button>
            <button
                @click="archiveTask"
                class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700"
            >
                {{ task.is_archived ? 'Restore' : 'Archive' }}
            </button>
            <button
                @click="showEditTaskModal = true"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
                Edit
            </button>
            <button
                @click="deleteTask"
                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
            >
                Delete
            </button>
        </div>

        <!-- Edit Task Modal -->
        <TaskModal
            v-if="showEditTaskModal"
            :task="task"
            @close="showEditTaskModal = false"
            @update="handleUpdateTask"
        />
    </div>
</template>

<script>
import TaskModal from '../../components/tasks/TaskModal.vue';

export default {
    components: {
        TaskModal,
    },
    data() {
        return {
            task: {},
            showEditTaskModal: false,
            errors: {},
        };
    },
    methods: {
        async fetchTask() {
            try {
                const response = await this.$api.get(`/tasks/${this.$route.params.id}`);
                this.task = response.data.data;
            } catch (error) {
                alert('Failed to fetch task.');
                this.$router.push({ name: 'Home' });
            }
        },
        async toggleCompletion() {
            try {
                if (this.task.is_completed) {
                    await this.$api.post(`/tasks/${this.task.id}/incomplete`);
                } else {
                    await this.$api.post(`/tasks/${this.task.id}/complete`);
                }
                this.fetchTask();
            } catch (error) {
                alert('Failed to update task status.');
            }
        },
        async archiveTask() {
            try {
                if (this.task.is_archived) {
                    await this.$api.post(`/tasks/${this.task.id}/restore`);
                } else {
                    await this.$api.post(`/tasks/${this.task.id}/archive`);
                }
                this.fetchTask();
            } catch (error) {
                alert('Failed to update task archive status.');
            }
        },
        async deleteTask() {
            if (!confirm('Are you sure you want to delete this task?')) return;

            try {
                await this.$api.delete(`/tasks/${this.task.id}`);
                alert('Task deleted successfully.');
                this.$router.push({ name: 'Home' });
            } catch (error) {
                alert('Failed to delete task.');
            }
        },
        async handleUpdateTask(updatedTaskData) {
            try {
                await this.$api.put(`/tasks/${this.task.id}`, updatedTaskData);
                this.showEditTaskModal = false;
                this.fetchTask();
                alert('Task updated successfully.');
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('An error occurred. Please try again.');
                }
            }
        },
    },
    mounted() {
        this.fetchTask();
    },
};
</script>

<style scoped>
/* Add custom styles if needed */
</style>
