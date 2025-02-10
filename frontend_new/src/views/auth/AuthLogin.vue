<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
            <form @submit.prevent="handleLogin" class="space-y-4">
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
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Login
                </button>
            </form>
            <div class="mt-6 text-center">
                <p class="text-sm">
                    Don't have an account?
                    <button
                        @click="navigateToRegister"
                        class="text-blue-600 hover:underline focus:outline-none"
                    >
                        Register here
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
            email: '',
            password: '',
            errors: {},
        };
    },
    methods: {
    async handleLogin() {
        try {
            console.log('handleLogin called');
            await this.$store.dispatch('auth/login', {
                email: this.email,
                password: this.password,
            });
            console.log('Token:', this.$store.state.auth.token);
            console.log('isAuthenticated:', this.$store.getters['auth/isAuthenticated']);
            console.log('Login successful');
            this.$router.push({ name: 'Home' }); // Always redirect to Home
        } catch (error) {
            console.error('Login error:', error);
            if (error.response && error.response.status === 422) {
                this.errors = error.response.data.errors;
            } else {
                alert('Login failed. Please try again.');
            }
        }
    },
    navigateToRegister() {
            this.$router.push({ name: 'Register' }); // Navigate to Register page
        },
},
};
</script>

<style scoped>
/* Add styles here */
</style>
