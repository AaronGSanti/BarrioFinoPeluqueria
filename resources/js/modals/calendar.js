import flatpickr from "flatpickr";
import confirmDatePlugin from "flatpickr/dist/plugins/confirmDate/confirmDate";
import Swal from "sweetalert2";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/plugins/confirmDate/confirmDate.css";
import { calendarRequest } from "@/services/calendar.service";
import { getBarbers } from "@/services/barbers.service";

export async function openCalendario({
    servicio_id,
    precio_total,
    cliente_id,
}) {
    if (!cliente_id) {
        await Swal.fire({
            icon: "warning",
            title: "Inicia sesion",
            text: "Debes iniciar sesion para reservar",
        });
        return;
    }
    return loadBarberos({ servicio_id, precio_total, cliente_id });
}

// 1)Cargamos los barberos antes de abrir el modal.
let barberos = [];
export async function loadBarberos({ servicio_id, precio_total, cliente_id }) {
    try {
        const response = await getBarbers();
        barberos = response.data.data;
    } catch (e) {
        await Swal.fire({
            icon: "error",
            title: "No se pudieron cargar los barberos",
            text: "Intenta de nuevo",
        });
        return;
    }

    if (!barberos || barberos.length === 0) {
        await Swal.fire({
            icon: "info",
            title: "Sin barberos disponibles",
            text: "No hay barberos disponibles en este momento.",
        });
        return;
    }

    let fp = null;

    const { value, isConfirmed } = await Swal.fire({
        title: "Elige tu cita",
        html: `
        <div style="text-align:left; margin-bottom:10px;">
        <label style="display:block; font-size:12px; opacity:.75; margin-bottom:6px;">Barbero</label>
        <select id="swal-barbero" class="swal2-input" style="width:100%; margin:0;">
            ${barberos
                .map(
                    (b, idx) =>
                        `<option value="${b.id}" ${
                            idx === 0 ? "selected" : ""
                        }>${b.name}</option>`
                )
                .join("")}
        </select>
        </div>

        <div id="fp-wrap" style="margin-top:10px;">
            <input id="swal-calendar" class="swal2-input" placeholder="Selecciona fecha y hora">
        </div>
        `,

        showCancelButton: true,
        confirmButtonText: "Guardar cita",
        cancelButtonText: "Cancelar",
        position: "center",
        focusConfirm: false,

        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),

        didOpen: async () => {
            const popup = Swal.getPopup();
            const input = popup.querySelector("#swal-calendar");
            const htmlContainer = popup.querySelector(".swal2-html-container");
            const selectBarbero = popup.querySelector("#swal-barbero");

            const applyBusyTimes = async () => {
                if (!fp) return;

                const barberoId = Number(selectBarbero.value);

                const selected = fp?.selectedDates?.[0] ?? new Date();
                const yyyy = selected.getFullYear();
                const mm = String(selected.getMonth() + 1).padStart(2, "0");
                const dd = String(selected.getDate()).padStart(2, "0");
                const dateISO = `${yyyy}-${mm}-${dd}`;

                let busy = [];
                try {
                    busy = await getBusySlots(barberoId, dateISO);
                } catch {
                    busy = [];
                }

                //Reconfiguramos flatpickr con los horarios ocupados
                fp.set("disable", [
                    function (date) {
                        const yyyy2 = date.getFullYear();
                        const mm2 = String(date.getMonth() + 1).padStart(
                            2,
                            "0"
                        );
                        const dd2 = String(date.getDate()).padStart(2, "0");
                        const iso2 = `${yyyy2}-${mm2}-${dd2}`;

                        if (iso2 !== dateISO) return false;

                        const hh = String(date.getHours()).padStart(2, "0");
                        const min = String(date.getMinutes()).padStart(2, "0");
                        const hm = `${hh}:${min}`;
                        return busy.includes(hm);
                    },
                ]);
            };

            fp = flatpickr(input, {
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
                onChange: async () => {
                    await applyBusyTimes();
                },
                onReady: async () => {
                    await applyBusyTimes();
                },
            });

            //Cuando cambie el barbero seleccionado , recargamos los horarios ocupados
            selectBarbero.addEventListener("change", async () => {
                await applyBusyTimes();
            });
        },

        preConfirm: async () => {
            const datetime = document
                .getElementById("swal-calendar")
                .value.trim();
            const barbero_id = document.getElementById("swal-barbero").value;

            if (!barbero_id) {
                Swal.showValidationMessage("Selecciona un barbero");
                return;
            }

            if (!datetime) {
                Swal.showValidationMessage("Selecciona fecha y hora");
                return;
            }

            const [fecha_hora, hm] = datetime.split(" ");

            const payload = {
                cliente_id: Number(cliente_id),
                barbero_id: Number(barbero_id),
                servicio_id: Number(servicio_id),
                fecha_hora,
                hora_inicio: `${hm}:00`,
                hora_fin: null,
                estado: "pendiente",
                precio_total: Number(precio_total),
            };

            try {
                await calendarRequest(payload);
                return true;
            } catch (error) {
                const status = error?.response?.status;
                if (status === 409) {
                    // (esto lo puedes dejar, pero no hace falta otro Swal)
                    Swal.showValidationMessage(
                        "Horario no disponible. Ya existe una cita en ese horario, elige otro."
                    );
                    return;
                }

                Swal.showValidationMessage(
                    "Error al crear la cita. Intenta de nuevo."
                );
                return;
            }
        },
    });

    if (!isConfirmed) return;

    await Swal.fire({
        icon: "success",
        title: "Cita guardada",
        timer: 1200,
        showConfirmButton: false,
    });
}
