import { router } from '@inertiajs/vue3'

export const passwordReset = (form) => {
    return axios
        .post(route("password.update"), form)
        .then((r) => {
            router.visit(route("owners"));
        })
};
