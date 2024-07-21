import { ref } from 'vue';

const socket = ref();

const connectWebSocket = (userId, showMessage, incrementUnreadMessages) => {
    socket.value = new WebSocket(
        `wss://${import.meta.env.VITE_APP_WEBSOCKETS_IP}:1915/?userid=${userId}`
    );

    socket.value.onopen = () => {
        console.log('WebSocket connection established');
    };

    socket.value.onmessage = (event) => {
        showMessage.info('У вас новое сообщение.');
        incrementUnreadMessages();
    };

    socket.value.onerror = (error) => {
        console.error('WebSocket error:', error);
    };

    socket.value.onclose = () => {
        console.log('WebSocket connection closed');
    };
};

const disconnectWebSocket = () => {
    if (socket.value) {
        socket.value.close();
    }
};

export { socket, connectWebSocket, disconnectWebSocket };
