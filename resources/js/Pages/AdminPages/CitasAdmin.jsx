import {
    ArrowDownTrayIcon,
    PencilSquareIcon,
    PlusIcon,
    TrashIcon,
} from "@heroicons/react/24/solid";
import openDeleteAppointmentModal from "./ModalsAppointments/DeleteAppointmentModal";
import AppointmentSearchAndDelete from "./ModalsAppointments/ComponentsAppointments/DeleteAndSearch";
import openUpdateAppointmentsModal from "./ModalsAppointments/UpdateAppointmentsModal";

const CitasAdmin = ({ citas = [] }) => {
    /**Formateo de dia para que se arregle en la vista. */
    const formatDate = (dateString) => {
        if (!dateString) return;
        return new Date(dateString).toLocaleDateString("es-ES");
    };

    /**Formateo de hora */
    const formatHour = (isoString) => {
        if (!isoString) return;
        const date = new Date(isoString);
        return date.toLocaleTimeString("es-ES", {
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    /**Colores para los estados */
    const estadoStyles = {
        pendiente: "bg-yellow-100 text-yellow-800",
        confirmada: "bg-blue-100 text-blue-800",
        cancelada: "bg-red-100 text-red-800",
        completada: "bg-green-100 text-green-800",
    };

    return (
        <>
            <div>
                <h1 className="font-bold text-2xl">Panel de Citas</h1>
            </div>

            <div className="mt-1">
                <p>Gestiona las citas de la aplicacion desde este panel.</p>
            </div>

            {/**Buscador y reset */}
            <div className="mt-3 flex items-center gap-2">
                <AppointmentSearchAndDelete />
            </div>

            <div className="flex flex-wrap gap-4 justify-end">
                <button className="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <PlusIcon className="h-5 w-7 inline-block mr-1" />
                    Agregar Cita
                </button>

                <button
                    className="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    onClick={() => {
                        window.location.href = route("admin.citas.export");
                    }}
                >
                    <ArrowDownTrayIcon className="h-5 w-7 inline-block mr-1" />
                    Exportar
                </button>
            </div>

            <div className="mt-6 overflow-hidden rounded-lg bg-white shadow">
                <table className="min-w-full text-sm">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="px-4 py-3 text-left">Cliente</th>
                            <th className="px-4 py-3 text-left">Barbero</th>
                            <th className="px-4 py-3 text-left">Servicio</th>
                            <th className="px-4 py-3 text-left">Fecha</th>
                            <th className="px-4 py-3 text-left">Hora</th>
                            <th className="px-4 py-3 text-left">Estado</th>
                            <th className="px-4 py-3 text-left">Precio</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y">
                        {citas.length === 0 ? (
                            <tr>
                                <td
                                    className="px-4 py-6 text-center text-gray-500"
                                    colSpan="4"
                                >
                                    No hay usuarios disponibles.
                                </td>
                            </tr>
                        ) : (
                            citas.map((cita) => (
                                <tr key={cita.id}>
                                    <td className="px-4 py-3">
                                        {cita.cliente.name}
                                    </td>
                                    <td className="px-4 py-3">
                                        {cita.barbero.name}
                                    </td>
                                    <td className="px-4 py-3">
                                        {cita.servicio.nombre}
                                    </td>
                                    <td className="px-4 py-3">
                                        {formatDate(cita.fecha_hora)}
                                    </td>
                                    <td className="px-4 py-3">
                                        {formatHour(cita.hora_inicio)}
                                    </td>
                                    <td className="px-4 py-3">
                                        <span
                                            className={`inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                                ${
                                                    estadoStyles[cita.estado] ??
                                                    "bg-gray-100 text-gray-700"
                                                }`}
                                        >
                                            {" "}
                                            {cita.estado}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3">
                                        {cita.precio_total}
                                    </td>

                                    <td className="px-4 py-3">
                                        <div className="flex items-center gap-3">
                                            {/**Editar */}
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    openUpdateAppointmentsModal(
                                                        cita
                                                    )
                                                }
                                            >
                                                <PencilSquareIcon className="h-5 w-5" />
                                            </button>

                                            {/**Eliminar */}
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    openDeleteAppointmentModal(
                                                        cita.id
                                                    )
                                                }
                                            >
                                                <TrashIcon className="h-5 w-5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>
        </>
    );
};

export default CitasAdmin;
