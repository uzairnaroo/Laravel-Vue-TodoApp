<template>
    <div class="flex justify-between items-center p-4 bg-white shadow-md rounded-lg">
        <!-- Previous Button -->
        <button
            :disabled="!meta?.prev_page_url"
            @click="$emit('paginate', meta.current_page - 1)"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed"
        >
            Previous
        </button>

        <!-- Page Info -->
        <span v-if="meta" class="text-gray-700 text-sm">
            Page <span class="font-bold">{{ meta.current_page }}</span> of
            <span class="font-bold">{{ meta.last_page }}</span>
        </span>

        <!-- Next Button -->
        <button
            :disabled="!meta?.next_page_url"
            @click="$emit('paginate', meta.current_page + 1)"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed"
        >
            Next
        </button>
    </div>
</template>

<script>
export default {
    props: {
        meta: {
            type: Object,
            required: true,
            default: () => ({
                current_page: 1,
                last_page: 1,
                prev_page_url: null,
                next_page_url: null,
            }),
        },
    },
};
</script>
