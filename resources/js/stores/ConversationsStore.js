import { defineStore } from "pinia";
import axios from "axios";

export const useConversationsStore = defineStore("conversations", {
    state: () => ({
        loading: false,
        conversation_id: null,
        conversations: {},
        conversationsMessages: {},
    }),
    actions: {
        async getConversations() {
            let url = route('conversations');
            this.loading = true;

            const response = await axios.get(url);
            this.conversations = response.data;
            this.loading = false;
        },
        async getConversationsMessages() {
            if (!this.conversation_id) return;
            let url = route('conversations.messages', {conversationId: this.conversation_id});
            this.loading = true;
            console.log(123)
            const response = await axios.get(url);
            this.conversationsMessages = response.data;
            this.loading = false;
        },
        addNewMessage(message, senderProfileURL, createdAt) { // Adjust these parameters as per requirement
            const user = {
                profile_photo_url: senderProfileURL,
            };

            const newMessage = {
                message,
                user,
                created_at_time: createdAt,
            };

            this.conversationsMessages.push(newMessage);
        },
    }
});