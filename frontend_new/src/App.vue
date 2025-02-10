<template>
  <div id="app">
    <nav class="bg-blue-600 text-white p-4 flex justify-between items-center">
      <div class="text-xl font-bold">Task App</div>
      <div>
        <!-- Home -->
        <router-link
          v-if="$route.path !== '/tasks/:id' && $route.path !== '/' && $route.path !== '/login' && $route.path !== '/register'"
          to="/"
          class="px-3 py-2 rounded hover:bg-blue-700"
        >
          Back
        </router-link>

        <!-- Sign In -->
        <router-link
          v-if="!isAuthenticated && $route.path !== '/login' && $route.path !== '/' && $route.path === '/tasks/:id'"
          to="/login"
          class="px-3 py-2 rounded hover:bg-blue-700"
        >
          Sign In
        </router-link>

        <!-- Register -->
        <router-link
          v-if="!isAuthenticated && $route.path !== '/register' && $route.path !== '/' && $route.path === '/tasks/:id'"
          to="/register"
          class="px-3 py-2 rounded hover:bg-blue-700"
        >
          Register
        </router-link>

        <!-- Sign Out -->
        <button
          v-if="$route.path == '/' || $route.path !== '/tasks/:id' && $route.path !== '/login' && $route.path !== '/register'"
          @click="handleLogout"
          class="px-3 py-2 rounded bg-red-600 hover:bg-red-700"
        >
          Sign Out
        </button>
      </div>
    </nav>
    <router-view></router-view>
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  name: 'App',
  computed: {
    ...mapState({
      isAuthenticated: (state) => state.auth.isAuthenticated,
    }),
  },
  methods: {
    handleLogout() {
    console.log('Sign Out button clicked');
    this.$store.dispatch('auth/logout')
      .then(() => {
        console.log('Logout successful');
        this.$router.push({ name: 'Login' }); // Redirect to Login after logout
      })
      .catch((error) => {
        console.error('Logout failed:', error);
      });
  },
  },
};
</script>

<style>
body {
  margin: 0;
  font-family: 'Arial', sans-serif;
}
</style>
