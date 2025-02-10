import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/HomePage.vue';
import Login from '../views/auth/AuthLogin.vue';
import Register from '../views/auth/AuthRegister.vue';
import TaskDetails from '../views/tasks/TaskDetails.vue';
import store from '../store';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: { requiresAuth: true },
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: { guest: true },
    },
    {
        path: '/tasks/:id',
        name: 'TaskDetails',
        component: TaskDetails,
        meta: { requiresAuth: true },
    },
];

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = store.getters['auth/isAuthenticated']; // Use the Vuex getter
    console.log('Routing to:', to.name, 'Authenticated:', isAuthenticated);

    if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
        // Redirect to Login if not authenticated
        next({ name: 'Login' });
    } else if (to.matched.some(record => record.meta.guest) && isAuthenticated) {
        // Redirect to Home if authenticated and accessing guest route
        next({ name: 'Home' });
    } else {
        next(); // Allow access
    }
});


export default router;
