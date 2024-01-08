import Login from "@/Components/Auth/Login.vue";
import Register from "@/Components/Auth/Register.vue";
import PasswordRecovery from "@/Components/Auth/PasswordRecovery.vue";
import { openModal, closeModal } from "jenesius-vue-modal";

const openRegister = () => {
    closeModal();
    openModal(Register);
};

const openLogin = () => {
    closeModal();
    openModal(Login);
};
const openPasswordRecovery = () => {
    closeModal();
    openModal(PasswordRecovery);
};

export { openRegister, openLogin, openPasswordRecovery };
