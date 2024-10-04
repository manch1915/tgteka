import { ref } from 'vue';

const socket = ref();

const connectWebSocket = (userId, showMessage, incrementUnreadMessages) => {
    socket.value = new WebSocket(
        `wss://${import.meta.env.VITE_APP_WEBSOCKETS_IP}:1915/?userid=${userId}`
    );

    socket.value.onmessage = (event) => {
        showMessage.info('У вас новое сообщение.');
        incrementUnreadMessages();
    };
};

const disconnectWebSocket = () => {
    if (socket.value) {
        socket.value.close();
    }
};

export { socket, connectWebSocket, disconnectWebSocket };
