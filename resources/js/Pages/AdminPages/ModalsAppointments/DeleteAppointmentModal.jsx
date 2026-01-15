import { router } from "@inertiajs/react";
import Swal from "sweetalert2";

const openDeleteAppointmentModal = async (citaId) => {
    const result = await Swal.fire({
        title: "¿Estas seguro?",
        text: "Esta accion no se puede deshacer. La cita sera eliminada permanentemente.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
    });

    if (result.isConfirmed) {
        router.delete(`/admin/citas/delete/${citaId}`, {
            onSuccess: () => {
                Swal.fire({
                    icon: "success",
                    title: "Cita eliminada con exito",
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

export default openDeleteAppointmentModal;
