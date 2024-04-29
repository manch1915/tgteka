import Login from "@/Components/Auth/Login.vue";
import Register from "@/Components/Auth/Register.vue";
import PasswordRecovery from "@/Components/Auth/PasswordRecovery.vue";
import {openModal, closeModal, onBeforeModalClose} from "jenesius-vue-modal";

const openRegister = () => {
    closeModal();
    openModal(Register);
    onBeforeModalClose(() => {
        axios.post(route('remember-url'), {slug: ''}).then(r => {

        })
    })
};

const openLogin = () => {
    closeModal();
    openModal(Login);
    onBeforeModalClose(() => {
        axios.post(route('remember-url'), {slug: ''}).then(r => {

        })
    })
};
const openPasswordRecovery = () => {
    closeModal();
    openModal(PasswordRecovery);
};

export { openRegister, openLogin, openPasswordRecovery };
