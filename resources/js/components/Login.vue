<!-- resources/js/components/Login.vue -->

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="bg-white p-8 rounded-2xl shadow-lg max-w-xs w-full relative">
            <h2 class="text-blue-500 text-2xl font-semibold mb-6">Login to MAAI</h2>
            <form @submit.prevent="login" class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-1">Email</label>
                    <input
                        type="email"
                        v-model="email"
                        required
                        class="w-full px-4 py-2 bg-gray-100 rounded-lg border focus:outline-none focus:ring-2 focus:ring-blue-300"
                    />
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">Password</label>
                    <input
                        type="password"
                        v-model="password"
                        required
                        class="w-full px-4 py-2 bg-gray-100 rounded-lg border focus:outline-none focus:ring-2 focus:ring-blue-300"
                    />
                </div>
                <button
                    type="submit"
                    class="w-full py-3 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 transition-colors"
                >
                    Login
                </button>
            </form>

            <!-- iOS-Style Error Alert -->
            <transition name="fade">
                <div
                    v-if="error"
                    class="fixed inset-0 bg-gray-900 bg-opacity-30 flex items-center justify-center z-10"
                >
                    <div class="bg-white max-w-xs w-11/12 rounded-3xl shadow-lg p-6 text-center">
                        <!-- Font Awesome Error Icon -->
                        <div class="text-red-500 mb-3">
                            <font-awesome-icon :icon="['fas', 'exclamation-triangle']" class="h-8 w-8" />
                        </div>

                        <!-- Error Message -->
                        <p class="text-gray-700 font-medium text-lg">Incorrect Credentials</p>
                        <p class="text-gray-500 mt-1 text-sm">
                            {{ error }}
                        </p>

                        <!-- Dismiss Button -->
                        <button
                            @click="dismissError"
                            class="mt-4 px-6 py-2 bg-blue-500 text-white font-semibold rounded-full hover:bg-blue-600 transition-colors"
                        >
                            Dismiss
                        </button>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import api from '../axios';

export default {
    data() {
        return {
            email: '',
            password: '',
            error: null
        };
    },
    methods: {
        async login() {
            try {
                const response = await api.post('/user/login', {
                    email: this.email,
                    password: this.password
                });
                const token = response.data.token;

                // Save the token to local storage
                localStorage.setItem('token', token);

                // Redirect to chat after successful login
                this.$router.push({ name: 'chat' });
            } catch (error) {
                // Show error message
                this.error = 'Please check your email and password and try again.';
            }
        },
        dismissError() {
            this.error = null;
        }
    }
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>
