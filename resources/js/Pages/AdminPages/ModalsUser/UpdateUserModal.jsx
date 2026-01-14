import { router } from "@inertiajs/react";
import Swal from "sweetalert2";

const openUpdateUserModal = async (user) => {
    const { value: formValues } = await Swal.fire({
        title: "Actualizar usuario",
        html: `
            <div class="swal-form">
                <div class="swal-row">
                    <label for="swal-name" class="swal-label">Nombre</label>
                    <input type="text" id="swal-name" class="swal-input" placeholder="Introduce nombre" value= "${
                        user.name ?? ""
                    }">
                </div>

                <div class="swal-row">
                    <label for="swal-email" class="swal-label">Email</label>
                    <input type="email" id="swal-email" class="swal-input" placeholder="Introduce email" value= "${
                        user.email ?? ""
                    }">
                </div>

                <div class="swal-row">
                    <label for="swal-role" class="swal-label">Rol</label>
                    <select id="swal-role" class="swal-input">
                    <option value="">Selecciona un rol</option>
                    <option value="cliente" ${
                        user.role === "cliente" ? "selected" : ""
                    }>Cliente</option>
                    <option value="barbero" ${
                        user.role === "barbero" ? "selected" : ""
                    }>Barbero</option>
                    <option value="admin" ${
                        user.role === "admin" ? "selected" : ""
                    }>Administrador</option>
                    </select>
                </div>

                <div class="swal-row">
                    <label for="swal-phone" class="swal-label">Teléfono</label>
                    <input type="text" id="swal-phone" class="swal-input" placeholder="Introduce telefono" value= "${
                        user.phone_number ?? ""
                    }">
                </div>

                <div class="swal-row">
                    <label for="swal-password" class="swal-label">Contraseña</label>
                    <input type="password" id="swal-password" class="swal-input" placeholder="Introduce contraseña" value= "${
                        user.password ?? ""
                    }">
                </div>
                </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: "Actualizar",
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

    router.put(`/admin/users/update/${user.id}`, formValues, {
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Usuario actualizado con exito",
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

export default openUpdateUserModal;
