// resources/js/router.js

import { createRouter, createWebHistory } from 'vue-router';
import Welcome from './components/Welcome.vue';
import Login from './components/Login.vue';
import ChatApp from './components/ChatApp.vue';

const routes = [
    { path: '/', name: 'welcome', component: Welcome },
    { path: '/login', name: 'login', component: Login },
    {
        path: '/chat',
        name: 'chat',
        component: ChatApp,
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Route guard to check authentication
router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('token'); // Check if token exists
    if (to.meta.requiresAuth && !isAuthenticated) {
        next({ name: 'login' }); // Redirect to login if not authenticated
    } else {
        next(); // Proceed to route
    }
});

export default router;
