import { router } from "@inertiajs/react";
import Swal from "sweetalert2";

const openUpdateServiceModal = async (service) => {
    const { value: formValues } = await Swal.fire({
        title: "Actualizar servicio",
        html: `
            <div class="swal-form">
                <div class="swal-row">
                    <label for="swal-nombre" class="swal-label">Nombre</label>
                    <input type="text" id="swal-nombre" class="swal-input" placeholder="Introduce nombre" value= "${service.nombre}">
                </div>

                <div class="swal-row">
                    <label for="swal-precio" class="swal-label">Precio</label>
                    <input type="text" id="swal-precio" class="swal-input" placeholder="Introduce precio" value= "${service.precio}">
                </div>

                <div class="swal-row">
                    <label for="swal-descripcion" class="swal-label">Descripcion</label>
                    <input type="text" id="swal-descripcion" class="swal-input" placeholder="Introduce descripcion" value= "${service.descripcion}">
                </div>

                <div class="swal-row">
                    <label for="swal-duracion" class="swal-label">Duracion</label>
                    <input type="text" id="swal-duracion" class="swal-input" placeholder="Introduce duracion" value= "${service.duracion}">
                </div>
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: "Actualizar",
        cancelButtonText: "Cancelar",

        preConfirm: () => {
            const nombre = document.getElementById("swal-nombre").value.trim();
            const precio = document.getElementById("swal-precio").value.trim();
            const descripcion = document
                .getElementById("swal-descripcion")
                .value.trim();
            const duracion = document
                .getElementById("swal-duracion")
                .value.trim();

            if (!nombre || !precio || !descripcion || !duracion) {
                Swal.showValidationMessage(
                    "Todos los campos son obligatorios."
                );
                return false;
            }

            return { nombre, precio, descripcion, duracion };
        },
    });

    if (!formValues) return;

    router.put(`/admin/services/update/${service.id}`, formValues, {
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Servicio actualizado con exito",
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
};

export default openUpdateServiceModal;