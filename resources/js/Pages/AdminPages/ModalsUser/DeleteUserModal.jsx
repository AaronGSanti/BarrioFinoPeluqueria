import { router } from "@inertiajs/react";
import Swal from "sweetalert2";

const openDeleteUserModal = async (userId) => {
    const result = await Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta accion no se puede deshacer. El usuario sera eliminado permanentemente.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
    });

    if (result.isConfirmed) {
        router.delete(`/admin/users/delete/${userId}`, {
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Usuario eliminado con exito",
                    timer: 1500,
                    showConfirmButton: false,
                });
            },
            onError: (errors) => {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                });
            },
        });
    }
};

export default openDeleteUserModal;
