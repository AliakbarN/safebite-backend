<!-- resources/js/components/Welcome.vue -->

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="max-w-xs w-full p-6 bg-white rounded-2xl shadow-lg text-center">
            <!-- Logo -->
            <img src="/assets/images/logo.png" alt="MAAI Logo" class="w-24 h-24 mx-auto rounded-full mb-6">

            <!-- Title -->
            <h1 class="text-blue-500 text-3xl font-semibold mb-4">Welcome to MAAI</h1>

            <!-- Subtitle -->
            <p class="text-gray-500 text-sm mb-6">Your team, together on one page.</p>

            <!-- Go to Chat Button -->
            <router-link
                to="/chat"
                class="block w-full py-3 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors"
            >
                Go to Chat
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Welcome',
};
</script>

<style scoped>
/* Scoped styles if needed */
</style>
