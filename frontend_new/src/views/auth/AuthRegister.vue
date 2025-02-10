<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold text-center mb-6">Register</h2>
            <form @submit.prevent="handleRegister" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input
                        type="text"
                        id="name"
                        v-model="name"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                    <span v-if="errors.name" class="text-sm text-red-500">{{ errors.name[0] }}</span>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="email"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                    <span v-if="errors.email" class="text-sm text-red-500">{{ errors.email[0] }}</span>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        type="password"
                        id="password"
                        v-model="password"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                    <span v-if="errors.password" class="text-sm text-red-500">{{ errors.password[0] }}</span>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        v-model="password_confirmation"
                        required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    />
                </div>
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Register
                </button>
            </form>
            <div class="mt-6 text-center">
                <p class="text-sm">
                    Already have an account?
                    <button
                        @click="navigateToLogin"
                        class="text-blue-600 hover:underline focus:outline-none"
                    >
                        Login here
                    </button>
                </p>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            errors: {},
        };
    },
    methods: {
        async handleRegister() {
            try {
                await this.$store.dispatch('auth/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                });
                this.$router.push({ name: 'Home' }); // Redirect to Home
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    alert('Registration failed. Please try again.');
                }
            }
        },
        navigateToLogin() {
            this.$router.push({ name: 'Login' }); // Navigate to Login page
        },
    },
};
</script>

<style scoped>
/* Add  styles here */
</style>
