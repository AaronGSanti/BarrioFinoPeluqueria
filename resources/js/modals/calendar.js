import flatpickr from "flatpickr";
import confirmDatePlugin from "flatpickr/dist/plugins/confirmDate/confirmDate";
import Swal from "sweetalert2";

import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/plugins/confirmDate/confirmDate.css";
import { calendarRequest } from "@/services/calendar.service";

export async function openCalendario() {
    const { value, isConfirmed } = await Swal.fire({
        title: "Elige tu cita",
        html: `
            <div id="fp-wrap" style="margin-bottom:12px">
                <input id="swal-calendar" class="swal2-input" placeholder="Selecciona fecha y hora">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: "Guardar cita",
        cancelButtonText: "Cancelar",
        position: "center",
        focusConfirm: false,

        didOpen: () => {
            const popup = Swal.getPopup();
            const input = popup.querySelector("#swal-calendar");
            const htmlContainer = popup.querySelector(".swal2-html-container");

            flatpickr(input, {
                inline: true, //Se ve dentro del modal
                enableTime: true,
                time_24hr: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",

                //Evita que el calendario se renderice fuera del modal
                appendTo: htmlContainer,

                plugins: [
                    confirmDatePlugin({
                        confirmText: "OK",
                        showAlways: true,
                        theme: "light",
                    }),
                ],
            });
        },

        preConfirm: () => {
            const datetime = document
                .getElementById("swal-calendar")
                ?.value?.trim();

            if (!datetime) {
                Swal.showValidationMessage("Selecciona fecha y hora");
                return;
            }
            return datetime;
        },
    });

    if (!isConfirmed) return;

    //Llamamos a la API
    try {
        const [fecha_hora, hm] = value.split("");
        const hora_inicio = `${hm}:00`;

        const cliente_id = window.authUser?.id;
        if (!cliente_id) {
            await Swal.fire({
                icon: "warning",
                title: "Inicia sesion",
                text: "Debes iniciar sesion para reservar",
            });
            return;
        }

        const payload = {
            cliente_id,
            barbero_id: null,
            servicio_id,
            fecha_hora,
            hora_inicio,
            hora_fin: null,
            estado: "pendiente",
            precio_total,
        };

        await calendarRequest(payload);

        await Swal.fire({
            icon: "success",
            title: "Cita guardada",
            timer: 1200,
            showConfirmButton: false,
        });
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: `Error (${error.response?.status || "?"})`,
            html: pretty,
        });
    }
}
