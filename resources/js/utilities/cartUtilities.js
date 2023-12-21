export const saveCart = (cart) => {
    localStorage.setItem("cart", JSON.stringify(cart));
};

export const loadCart = () => {
    return JSON.parse(localStorage.getItem("cart")) || [];
};

export const isInCart = (channel) => {
    const cart = loadCart();
    return cart.findIndex(ch => ch.id === channel.id) > -1;
};

export const generateFormatArray = (channel) => [
    { label: "1/24", value: "format_one_price" },
    { label: "2/48", value: "format_two_price" },
    { label: "3/72", value: "format_three_price" },
    { label: "3/без удаления", value: "no_deletion_price" },
].filter(item => channel[item.value] !== 0);
