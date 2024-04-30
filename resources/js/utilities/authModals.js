import Login from "@/Components/Auth/Login.vue";
import Register from "@/Components/Auth/Register.vue";
import PasswordRecovery from "@/Components/Auth/PasswordRecovery.vue";
import {openModal, closeModal} from "jenesius-vue-modal";

const openRegister = async () => {
    await closeModal();
    const modal = await openModal(Register);
    modal.onclose = (event) => {
        axios.post(route('remember-url'), {slug: ''}).then(r => {

        })
    }
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
