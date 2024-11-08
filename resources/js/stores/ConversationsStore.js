import { defineStore } from "pinia";
import axios from "axios";

export const useConversationsStore = defineStore("conversations", {
    state: () => ({
        loading: false,
        conversation_id: null,
        conversations: {},
        conversationsMessages: {},
        showChat: true
    }),
    actions: {
        async getConversations(search = "") {

            search = search.trim()

            let url = route('conversations', {search});
            this.loading = true;

            const response = await axios.get(url);
            this.conversations = response.data;
            this.loading = false;
        },
        async getConversationsMessages() {
            if (!this.conversation_id) return;
            let url = route('conversations.messages', {conversationId: this.conversation_id});
            this.loading = true;
            const response = await axios.get(url);
            this.conversationsMessages = response.data;
            this.loading = false;
        },
        addNewMessage(message, username, createdAt, content_type) { // Adjust these parameters as per requirement
            const user = {
                username: username,
            };

            const newMessage = {
                message,
                user,
                created_at_time: createdAt,
                content_type
            };

            this.conversationsMessages.push(newMessage);
        },
        openChat() {
            if(window.innerWidth <= 768) {
                this.showChat = false;
            }
        },
        goBack() {
            if(window.innerWidth <= 768) {
                this.showChat = true;
            }
        },
        updateUnreadCount(chatId, unreadCount) {
            // Find the chat in the conversations list and update its unread_count
            const chatToUpdate = this.conversations.find(chat => chat.id === chatId);
            if (chatToUpdate) {
                chatToUpdate.unread_count = unreadCount;
            }
        }
    }
});
