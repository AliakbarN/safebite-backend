<template>
    <div class="flex items-center justify-center h-screen">
        <div class="flex flex-col z-50 md:h-[90%] h-full mx-auto md:border md:rounded-xl md:w-[60%] w-full bg-gray-50">
            <!-- Chat Header -->
            <header class="text-gray-800 py-3 px-4 flex items-center justify-between border-b border-gray-200">
                <h2 class="text-lg font-semibold">AI Chat</h2>
                <button @click="logout" class="text-blue-500 font-medium focus:outline-none">Log out</button>
            </header>

            <!-- Chat Messages -->
            <div ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-3">
                <!-- Placeholder message for empty chat history -->
                <div v-if="messages.length === 0 && !loading" class="text-center text-gray-500 py-6">
                    No messages yet. Start the conversation!
                </div>

                <!-- Display chat messages if available -->
                <div
                    v-for="(message, index) in messages"
                    :key="index"
                    :class="[ 'flex items-end', message.role === 'user' ? 'justify-end' : 'justify-start' ]"
                >
                    <div
                        :class="[
                            'px-4 py-2 max-w-[80%] rounded-lg text-sm md:text-base shadow',
                            message.role === 'user' ? 'bg-blue-500 text-white rounded-br-none' : 'bg-gray-200 text-gray-900 rounded-bl-none'
                        ]"
                    >
                        {{ message.content }}
                    </div>
                </div>

                <!-- Loading Indicator -->
                <div v-if="loading" class="text-center text-gray-500 py-6">
                    Bot is typing...
                </div>
            </div>

            <!-- Input Section -->
            <div class="border-t border-gray-200 p-4 flex items-center space-x-2">
                <input
                    v-model="input"
                    type="text"
                    placeholder="Type a message..."
                    class="flex-1 bg-gray-100 rounded-full px-4 py-2 text-gray-700 outline-none focus:ring focus:ring-blue-300"
                    @keyup.enter="sendMessage"
                    :disabled="loading"
                />
                <button
                    @click="sendMessage"
                    class="bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 focus:outline-none"
                    :disabled="loading"
                >
                    Send
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import api from '../axios';

export default {
    data() {
        return {
            input: '',
            messages: [],
            loading: false,
        };
    },
    mounted() {
        const token = localStorage.getItem('token');
        if (!token) {
            this.$router.push({ name: 'login' });
        } else {
            this.loadChatHistory();
            this.listenForMessages();
        }
    },
    methods: {
        async loadChatHistory() {
            try {
                const response = await api.get('/chat/user/getChatHistory');
                const history = JSON.parse(response.data.chat) || [];

                this.messages = history
                    .filter(entry => entry.role !== 'system') // Exclude 'system' role
                    .map(entry => ({
                        role: entry.role,
                        content: entry.content,
                    }));

                this.scrollToBottom();
            } catch (error) {
                console.error("Error loading chat history:", error);
                if (error.response && error.response.status === 401) {
                    this.$router.push({ name: 'login' });
                }
            }
        },
        listenForMessages() {
            window.Echo.channel('chat').listen('BotResponseReceived', (e) => {
                this.loading = false; // Hide loading indicator on response
                let response = JSON.parse(e.userChat.chat);
                let botResponse = response.slice(-1)[0];

                // Ensure only the latest message from bot is added
                if (botResponse.role === 'assistant') {
                    const botMessage = {
                        role: 'assistant',
                        content: botResponse.content,
                    };
                    this.addMessage(botMessage);
                }
            });
        },
        addMessage(message) {
            this.messages.push(message);
            this.$nextTick(() => {
                this.scrollToBottom();
            });
        },
        async sendMessage() {
            if (this.input.trim() === '') return;

            const userMessage = {
                role: 'user',
                content: this.input,
            };
            this.addMessage(userMessage);

            this.loading = true; // Show loading indicator while waiting for bot response

            try {
                await api.post('/chat/user/sendMessage', { text: this.input });
            } catch (error) {
                console.error("Error sending message:", error);
            }

            this.input = ''; // Clear input after sending
        },
        scrollToBottom() {
            const container = this.$refs.messagesContainer;
            container.scrollTop = container.scrollHeight;
        },
        logout() {
            localStorage.removeItem('token');
            this.$router.push({ name: 'login' });
        },
    },
};
</script>

<style scoped>
/* Scoped styles for smooth fade transition */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter, .fade-leave-to {
    opacity: 0;
}

/* Additional mobile-specific styles */
@media (max-width: 640px) {
    .text-lg {
        font-size: 1rem;
    }

    .max-w-xs {
        max-width: 90%;
    }

    .p-4 {
        padding: 1rem;
    }
}
</style>
