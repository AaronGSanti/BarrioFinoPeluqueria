import { loginRequest } from "@/services/auth.service";
import Swal from "sweetalert2";

export async function openLoginModal() {
    const { value, isConfirmed } = await Swal.fire({
        title: "Iniciar sesion",
        html: `
            <input id="email" class="swal2-input" placeholder="Ingrese nombre" type="email">
            <input id="password" class="swal2-input" placeholder="Ingrese contraseña" type="password">`,
        showCancelButton: true,
        confirmButtonText: "Iniciar sesion",

        preConfirm: () => {
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            if (!email || !password) {
                Swal.showValidationMessage  ("Completa todo");
                return;
            }
            return { email, password };
        },
    });

    if(!isConfirmed) return;

    try{
        await loginRequest(value);
        await Swal.fire({
            icon: "success",
            title: "Bienvenido",
            timer:900,
            showConfirmButton: false
        });
    } catch(error){
        const msg="Error al iniciar sesion";
        Swal.fire({
            icon:"error",
            title:"Oops hay un error",
            text:msg
        });
    }
}
