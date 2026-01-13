import { router } from "@inertiajs/react";
import Swal from "sweetalert2";

const openCreateUserModal = async () => {
    const { value: formValues } = await Swal.fire({
        title: "Crear nuevo usuario",
        html: `
            <div class="swal-form">
                <div class="swal-row">
                    <label for="swal-name" class="swal-label">Nombre</label>
                    <input type="text" id="swal-name" class="swal-input" placeholder="Introduce nombre">
                </div>

                <div class="swal-row">
                    <label for="swal-email" class="swal-label">Email</label>
                    <input type="email" id="swal-email" class="swal-input" placeholder="Introduce email">
                </div>

                <div class="swal-row">
                    <label for="swal-role" class="swal-label">Rol</label>
                    <select id="swal-role" class="swal-input">
                    <option value="">Selecciona un rol</option>
                    <option value="cliente">Cliente</option>
                    <option value="barbero">Barbero</option>
                    <option value="admin">Administrador</option>
                    </select>
                </div>

                <div class="swal-row">
                    <label for="swal-phone" class="swal-label">Teléfono</label>
                    <input type="text" id="swal-phone" class="swal-input" placeholder="Introduce telefono">
                </div>

                <div class="swal-row">
                    <label for="swal-password" class="swal-label">Contraseña</label>
                    <input type="password" id="swal-password" class="swal-input" placeholder="Introduce contraseña">
                </div>
                </div>

        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: "Crear",
        cancelButtonText: "Cancelar",
        preConfirm: () => {
            const name = document.getElementById("swal-name").value.trim();
            const email = document.getElementById("swal-email").value.trim();
            const role = document.getElementById("swal-role").value.trim();
            const phone_number = document
                .getElementById("swal-phone")
                .value.trim();
            const password = document.getElementById("swal-password").value;

            if (!name || !email || !phone_number || !password) {
                Swal.showValidationMessage(
                    "Todos los campos son obligatorios."
                );
                return false;
            }

            if (password.length < 8) {
                Swal.showValidationMessage(
                    "La contraseña debe tener al menos 8 caracteres."
                );
                return false;
            }

            return { name, email, role, phone_number, password };
        },
    });

    if (!formValues) return;

    router.post("/admin/users/store", formValues, {
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Usuario creado con éxito",
                timer: 1500,
                showConfirmButton: false,
            });
        },
        onError: (errors) => {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: firstError,
            });
        },
    });
};

export default openCreateUserModal;
