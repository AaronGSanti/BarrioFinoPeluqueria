import { router } from "@inertiajs/react";
import Swal from "sweetalert2";

const openDeleteServiceModal = async (serviceId) => {
    const result = await Swal.fire({
        title: "¿Estas seguro?",
        text: "Esta accion no se puede deshacer. El servicio sera eliminado permanentemente.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
    });

    if (result.isConfirmed) {
        router.delete(`/admin/services/delete/${serviceId}`, {
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Servicio eliminado con exito",
                    timer: 1500,
                    showConfirmButton: false,
                });
            },
            onError: () => {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                });
            },
        });
    }
};

export default openDeleteServiceModal;
