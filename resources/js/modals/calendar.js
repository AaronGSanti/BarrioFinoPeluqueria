import flatpickr from "flatpickr";
import confirmDatePlugin from "flatpickr/dist/plugins/confirmDate/confirmDate";
import Swal from "sweetalert2";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/plugins/confirmDate/confirmDate.css";
import { calendarRequest } from "@/services/calendar.service";

export async function openCalendario({
    servicio_id,
    precio_total,
    cliente_id,
}) {
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
                inline: true,
                enableTime: true,
                time_24hr: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
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

    if (!cliente_id) {
        await Swal.fire({
            icon: "warning",
            title: "Inicia sesión",
            text: "Debes iniciar sesión para reservar",
        });
        return;
    }

    try {
        // value = "YYYY-MM-DD HH:mm"
        const [fecha_hora, hm] = value.split(" ");
        const payload = {
            cliente_id: Number(cliente_id),
            barbero_id: null,
            servicio_id: Number(servicio_id),
            fecha_hora,
            hora_inicio: `${hm}:00`,
            hora_fin: null,
            estado: "pendiente",
            precio_total: Number(precio_total),
        };

        console.log("Payload cita:", payload);

        await calendarRequest(payload);

        await Swal.fire({
            icon: "success",
            title: "Cita guardada",
            timer: 1200,
            showConfirmButton: false,
        });
    } catch (error) {
        const msg = "Error al crear la cita";
        Swal.fire({
            icon: "error",
            title: "Oops hay un error",
            text: msg,
        });
    }
}
