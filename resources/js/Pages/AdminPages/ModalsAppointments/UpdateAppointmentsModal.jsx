import { router } from "@inertiajs/react";
import axios from "axios";
import Swal from "sweetalert2";

const openUpdateAppointmentsModal = async (cita) => {
    let barberos = [];
    let servicios = [];

    /**Formateo de dia para que se arregle en la vista. */
    const formatDateForInput = (dateString) => {
        if (!dateString) return "";
        return new Date(dateString).toISOString().split("T")[0];
    };

    const formatHourForInput = (isoString) => {
        if (!isoString) return "";
        const d = new Date(isoString);
        const hh = String(d.getHours()).padStart(2, "0");
        const mm = String(d.getMinutes()).padStart(2, "0");
        return `${hh}:${mm}`; // HH:mm
    };

    //Cargamos los datos que queremos traer que tenemos desde la ruta
    try {
        const [barberosRes, serviciosRes] = await Promise.all([
            axios.get(route("admin.citas.barberos")),
            axios.get(route("admin.citas.servicios")),
        ]);

        barberos = barberosRes.data.data;
        servicios = serviciosRes.data.data;
    } catch (error) {
        await Swal.fire({
            icon: "error",
            title: "error",
            text: "No se pudieron cargar los barberos",
        });
        return;
    }

    const { value: formValues } = await Swal.fire({
        title: "Actualizar cita",
        html: `
            <div class="swal-form">
                <div class="swal-row">
                    <label for="swal-cliente" class="swal-label">Cliente</label>
                    <input type="text" id="swal-cliente" class="swal-input" value= "${
                        cita.cliente.name ?? ""
                    }">
                </div>

                <div class="swal-row">
                    <label class="swal-label">Barbero</label>
                    <select id="swal-barbero" class="swal-input">
                        <option value="">Selecciona un barbero</option>
                        ${barberos
                            .map(
                                (b) => `
                                <option value="${b.id}" ${
                                    Number(cita.barbero_id) === Number(b.id)
                                        ? "selected"
                                        : ""
                                }>
                                    ${b.name}
                                </option>
                            `
                            )
                            .join("")}
                    </select>
                </div>

                <div class="swal-row">
                    <label class="swal-label">Servicio</label>
                    <select id="swal-servicio" class="swal-input">
                        <option value="">Selecciona un servicio</option>
                        ${servicios
                            .map(
                                (s) => `
                                <option value="${s.id}" ${
                                    Number(cita.servicio_id) === Number(s.id)
                                        ? "selected"
                                        : ""
                                }>
                                    ${s.nombre}
                                </option>
                            `
                            )
                            .join("")}
                    </select>
                </div>

                <div class="swal-row">
                    <label for="swal-label" class="swal-label">Fecha</label>
                    <input type="date" id="swal-fecha" class="swal-input" value= "${formatDateForInput(
                        cita.fecha_hora
                    )}">
                </div>

                <div class="swal-row">
                    <label for="swal-label" class="swal-label">Hora</label>
                    <input type="time" id="swal-hora" class="swal-input" value= "${formatHourForInput(
                        cita.hora_inicio
                    )}">
                </div>

                <div class="swal-row">
                    <label for="swal-estado" class="swal-label">Rol</label>
                    <select id="swal-estado" class="swal-input">
                    <option value="">Selecciona un estado</option>
                    <option value="pendiente" ${
                        cita.estado === "pendiente" ? "selected" : ""
                    }>Pendiente</option>
                    <option value="confirmada" ${
                        cita.estado === "confirmada" ? "selected" : ""
                    }>Confirmada</option>
                    <option value="cancelada" ${
                        cita.estado === "cancelada" ? "selected" : ""
                    }>Cancelada</option>
                    <option value="completada" ${
                        cita.estado === "completada" ? "selected" : ""
                    }>Completada</option>
                    </select>
                </div>

                <div class="swal-row">
                    <label for="swal-label" class="swal-label">Precio</label>
                    <input type="text" id="swal-precio" class="swal-input" value= "${
                        cita.precio_total
                    }">
                </div>
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: "Actualizar",
        cancelButtonText: "Cancelar",

        preConfirm: () => {
            const cliente = document
                .getElementById("swal-cliente")
                .value.trim();
            const barbero = document
                .getElementById("swal-barbero")
                .value.trim();
            const servicio = document
                .getElementById("swal-servicio")
                .value.trim();
            const fecha = document.getElementById("swal-fecha").value.trim();
            const hora = document.getElementById("swal-hora").value.trim();
            const estado = document.getElementById("swal-estado").value.trim();
            const precio = document.getElementById("swal-precio").value.trim();

            if (
                !cliente ||
                !barbero ||
                !servicio ||
                !fecha ||
                !hora ||
                !estado ||
                !precio
            ) {
                Swal.showValidationMessage(
                    "Todos los campos son obligatorios."
                );
                return false;
            }

            return {
                cliente_id: cita.cliente_id, 
                barbero_id: Number(barbero),
                servicio_id: Number(servicio),
                fecha_hora: fecha,
                hora_inicio: `${hora}:00`, 
                estado,
                precio_total: Number(precio),
            };
        },
    });

    if (!formValues) return;

    router.put(`admin/citas/update/${cita.id}`, formValues, {
        onSuccess: () => {
            Swal.fire({
                icon: "success",
                title: "Cita actualizada con exito",
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

export default openUpdateAppointmentsModal;
