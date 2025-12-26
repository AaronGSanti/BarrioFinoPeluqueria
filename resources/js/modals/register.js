import Swal from "sweetalert2";
import axios from "axios";
import { router } from "@inertiajs/react";
import { registerRequest } from "@/services/auth.service";

export async function openRegisterModal() {
    const { value, isConfirmed } = await Swal.fire({
        title: "Registro",
        html: `
        <input id="name" class="swal2-input" placeholder="Ingrese nombre" type="text">
        <input id="phone_number" class="swal2-input" placeholder="Ingrese Nº telefono" type="text">
        <input id="email" class="swal2-input" placeholder="Ingrese email" type="email">
        <input id="password" class="swal2-input" placeholder="Ingrese contraseña" type="password">
    `,
        showCancelButton: true,
        confirmButtonText: "Registrarse",
        focusConfirm: false,
        preConfirm: () => {
            const name = document.getElementById("name")?.value?.trim();
            const email = document.getElementById("email")?.value?.trim();
            const password = document.getElementById("password")?.value;
            const phone_number = document
                .getElementById("phone_number")
                ?.value?.trim();

            if (!name || !email || !password || !phone_number) {
                Swal.showValidationMessage("Completa todo");
                return;
            }

            return { name, email, password, phone_number };
        },
    });

    if (!isConfirmed) return;

    try {
        // 1) Creamos usuario en la API.
        await registerRequest(value);

        // 2) Login web (Breeze) para que Inertia tenga sesión y auth.user
        await axios.post(
            "/login",
            {
                email: value.email,
                password: value.password,
            },
            {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            }
        );

        await Swal.fire({
            icon: "success",
            title: "Bienvenido",
            timer: 900,
            showConfirmButton: false,
        });

        // 3) refresca props de Inertia (sin F5)
        router.reload({ only: ["auth"] });
    } catch (error) {
        const msg = "Error al registrarse";
        Swal.fire({
            icon: "error",
            title: "Oops hay un error",
            text: msg,
        });
    }
}
