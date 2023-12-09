import {useMessage} from 'naive-ui'

const message = useMessage();
export function saveCart(cart) {
    localStorage.setItem("cart", JSON.stringify(cart));
}

export function loadCart() {
    return JSON.parse(localStorage.getItem("cart")) || [];
}

export function generateFormatArray(channel) {
    return [
        { label: "1/24", value: "format_one_price" },
        { label: "2/48", value: "format_two_price" },
        { label: "3/72", value: "format_three_price" },
        { label: "3/без удаления", value: "no_deletion_price" },
    ].filter((item) => channel[item.value] !== 0);
}

export async function addChannelToFavorites(channel) {
    try {
        const response = await axios.post(route("catalog.channels.favorite"), { channel_id: channel.id });

        if (response.data.status === "success") {
            const isFav = response.data.message.includes("added");
            message.info(`Канал ${channel.channel_name} ${`${isFav ? "добавлен в избранное" : "удален из избранних"}`}`);
            return isFav;
        } else {
            message.error("Возникла проблема с добавлением канала в избранное.");
        }
    } catch (error) {
        message.error("Произошла ошибка: ", error);
    }
};
