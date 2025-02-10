<template>
    <div class="p-6 bg-white rounded-lg shadow-md border border-gray-300">
        <h3 class="text-xl font-bold mb-2">{{ task.title }}</h3>
        <p class="text-gray-700 mb-2">{{ task.description }}</p>
        <p class="text-sm text-gray-500 mb-1">
            <span class="font-semibold">Priority:</span> {{ task.priority }}
        </p>
        <p class="text-sm text-gray-500 mb-1">
            <span class="font-semibold">Due Date:</span> {{ task.due_date }}
        </p>
        <p class="text-sm text-gray-500 mb-4">
            <span class="font-semibold">Status:</span>
            <span
                :class="task.is_completed ? 'text-green-600' : 'text-red-600'"
            >
                {{ task.is_completed ? 'Completed' : 'Todo' }}
            </span>
        </p>

        <!-- Tags -->
        <div class="mb-4">
            <span
                v-for="tag in task.tags"
                :key="tag"
                class="inline-block bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded-full mr-2 mb-2"
            >
                {{ tag }}
            </span>
        </div>

        <!-- Attachments -->
        <div class="mb-4">
            <p class="text-sm font-semibold text-gray-700">Attachments:</p>
            <ul class="list-disc list-inside">
                <li
                    v-for="attachment in task.attachments"
                    :key="attachment.id"
                    class="text-blue-600 hover:underline"
                >
                    <a :href="attachment.url" target="_blank">Download</a>
                </li>
            </ul>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center mt-4">
            <button
                @click="toggleCompletion"
                class="bg-green-600 text-white px-3 py-2 rounded-md hover:bg-green-700"
            >
                {{ task.is_completed ? 'Mark as Incomplete' : 'Mark as Complete' }}
            </button>
            <button
                @click="archiveTask"
                class="bg-yellow-600 text-white px-3 py-2 rounded-md hover:bg-yellow-700"
            >
                {{ task.is_archived ? 'Restore' : 'Archive' }}
            </button>
            <button
                @click="deleteTask"
                class="bg-red-600 text-white px-3 py-2 rounded-md hover:bg-red-700"
            >
                Delete
            </button>
            <button
                @click="editTask"
                class="bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700"
            >
                Edit
            </button>
            <router-link
                :to="{ name: 'TaskDetails', params: { id: task.id } }"
                class="text-blue-600 hover:underline"
            >
                View
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        task: {
            type: Object,
            required: true,
        },
    },
    methods: {
        async toggleCompletion() {
            try {
                if (this.task.is_completed) {
                    await this.$api.post(`/tasks/${this.task.id}/incomplete`);
                } else {
                    await this.$api.post(`/tasks/${this.task.id}/complete`);
                }
                this.$emit('update');
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
                this.$emit('update');
            } catch (error) {
                alert('Failed to update task archive status.');
            }
        },
        async deleteTask() {
            if (!confirm('Are you sure you want to delete this task?')) return;

            try {
                await this.$api.delete(`/tasks/${this.task.id}`);
                this.$emit('update');
                alert('Task deleted successfully.');
            } catch (error) {
                alert('Failed to delete task.');
            }
        },
        editTask() {
            this.$emit('edit', this.task); // Emit event to the parent component
        },
    },
};
</script>


<style scoped>
/* Add styles here */
</style>
