import './bootstrap';
import { createApp } from 'vue';
import ChatApp from './components/ChatApp.vue';

const chatApp = createApp(ChatApp);
chatApp.mount('#chat-app');
