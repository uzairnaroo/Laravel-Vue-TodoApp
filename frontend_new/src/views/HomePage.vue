<template>
    <div class="min-h-screen bg-gray-100 p-8">
        <h1 class="text-4xl font-bold text-center mb-6">My Tasks</h1>
        
        <!-- Success Message -->
        <div v-if="message" class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ message }}
        </div>

        <!-- Create Task Button -->
        <div class="flex justify-end mb-4">
            <button
                @click="openTaskModal"
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
            >
                Create Task
            </button>
        </div>

        <!-- Filters Section -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-6">
            <h2 class="text-lg font-bold mb-2">Filters</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <select v-model="filters.sort_by" class="p-2 border rounded">
                    <option value="">Sort By</option>
                    <option value="created_at">Created At</option>
                    <option value="priority">Priority</option>
                </select>
                <select v-model="filters.sort_order" class="p-2 border rounded">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
                <select v-model="filters.is_completed" class="p-2 border rounded">
                    <option value="">All</option>
                    <option :value="1">Completed</option>
                    <option :value="0">Todo</option>
                </select>
                <select v-model="filters.priority" class="p-2 border rounded">
                    <option value="">Priority</option>
                    <option value="Urgent">Urgent</option>
                    <option value="High">High</option>
                    <option value="Normal">Normal</option>
                    <option value="Low">Low</option>
                </select>
                <input
                    type="date"
                    v-model="filters.due_date_from"
                    class="p-2 border rounded"
                />
                <input
                    type="date"
                    v-model="filters.due_date_to"
                    class="p-2 border rounded"
                />
                <input
                    type="text"
                    v-model="filters.search"
                    placeholder="Search by Title"
                    class="p-2 border rounded"
                />
            </div>
            <div class="mt-4 flex gap-4">
                <button
                    @click="applyFilters"
                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700"
                >
                    Apply
                </button>
                <button
                    @click="resetFilters"
                    class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700"
                >
                    Reset
                </button>
            </div>
        </div>

        <!-- Task List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <TaskCard
                v-for="task in tasks.data"
                :key="task.id"
                :task="task"
                @update="fetchTasks"
                @edit="openTaskModal"
            />
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <Pagination
                :meta="tasks.meta"
                @paginate="fetchTasks"
            />
        </div>

        <!-- Create/Update Task Modal -->
        <TaskModal
            v-if="showTaskModal"
            :task="currentTask"
            @close="closeTaskModal"
            @update="fetchTasks"
            @success="showSuccessMessage"
        />
    </div>
</template>

<script>
import TaskCard from '../components/tasks/TaskCard.vue';
import TaskModal from '../components/tasks/TaskModal.vue';
import Pagination from '../components/AppPagination.vue';

export default {
    components: {
        TaskCard,
        TaskModal,
        Pagination,
    },
    data() {
        return {
            tasks: {},
            currentTask: null, // Stores the task being edited or null for creation
            showTaskModal: false, // Determines modal visibility
            filters: {
                sort_by: '',
                sort_order: 'asc',
                is_completed: '',
                priority: '',
                due_date_from: '',
                due_date_to: '',
                search: '',
            },
            message: null, // Displays success message
        };
    },
    methods: {
        openTaskModal(task = null) {
            console.log(task ? 'Editing Task' : 'Creating Task');
            this.currentTask = task; // Set task for editing, or null for creation
            this.showTaskModal = true; // Show the modal
        },
        closeTaskModal() {
            this.currentTask = null; // Reset task data
            this.showTaskModal = false; // Hide the modal
        },
        async fetchTasks(page = 1) {
            try {
                let query = `?page=${page}`;

                if (this.filters.sort_by) {
                    query += `&sort_by=${this.filters.sort_by}&sort_order=${this.filters.sort_order}`;
                }

                if (this.filters.is_completed !== '') {
                    query += `&is_completed=${this.filters.is_completed}`;
                }

                if (this.filters.priority) {
                    query += `&priority=${this.filters.priority}`;
                }

                if (this.filters.due_date_from && this.filters.due_date_to) {
                    query += `&due_date_from=${this.filters.due_date_from}&due_date_to=${this.filters.due_date_to}`;
                }

                if (this.filters.search) {
                    query += `&search=${this.filters.search}`;
                }

                const response = await this.$api.get(`/tasks${query}`);
                const data = response.data;

                // Map links to prev_page_url and next_page_url
                data.meta.prev_page_url = data.links.prev;
                data.meta.next_page_url = data.links.next;

                this.tasks = data;
            } catch (error) {
                alert('Failed to fetch tasks.');
            }
        },
        applyFilters() {
            this.fetchTasks(); // Apply filters and fetch tasks
        },
        resetFilters() {
            this.filters = {
                sort_by: '',
                sort_order: 'asc',
                is_completed: '',
                priority: '',
                due_date_from: '',
                due_date_to: '',
                search: '',
            };
            this.fetchTasks(); // Reset filters and fetch tasks
        },
        showSuccessMessage(message) {
            this.message = message; // Set success message
            setTimeout(() => {
                this.message = null; // Clear message after 3 seconds
            }, 3000);
        },
    },
    mounted() {
        this.fetchTasks(); // Fetch tasks when component mounts
    },
};
</script>
