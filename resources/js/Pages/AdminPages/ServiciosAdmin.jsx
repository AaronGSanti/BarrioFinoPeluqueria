import {
    ArrowDownTrayIcon,
    PencilSquareIcon,
    PlusIcon,
    TrashIcon,
} from "@heroicons/react/24/solid";
import ServiceSearchAndDelete from "./ModalsServices/ComponentsServices/DeleteAndSearch";
import openCreateServiceModal from "./ModalsServices/CreateServiceModal";
import openDeleteServiceModal from "./ModalsServices/DeleteServiceModal";
import openUpdateServiceModal from "./ModalsServices/UpdateServiceModal";

const ServiciosAdmin = ({ services = [] }) => {
    return (
        <>
            <div>
                <h1 className="font-bold text-2xl">Panel de Servicios</h1>
            </div>

            <div className="mt-1">
                <p>Gestiona los servicios de la aplicacion desde este panel.</p>
            </div>

            {/**Buscador y reset */}
            <div className="mt-3 flex items-center gap-2">
                <ServiceSearchAndDelete />
            </div>

            <div className="flex flex-wrap gap-4 justify-end">
                <button
                    className="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    onClick={openCreateServiceModal}
                >
                    <PlusIcon className="h-5 w-7 inline-block mr-1" />
                    Agregar Servicio
                </button>

                <button className="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                onClick={() => {
                    window.location.href = route("admin.service.export");
                }}>
                    <ArrowDownTrayIcon className="h-5 w-7 inline-block mr-1" />
                    Exportar
                </button>
            </div>

            <div className="mt-6 overflow-hidden rounded-lg bg-white shadow">
                <table className="min-w-full text-sm">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="px-4 py-3 text-left">Nombre</th>
                            <th className="px-4 py-3 text-left">Precio</th>
                            <th className="px-4 py-3 text-left">Descripcion</th>
                            <th className="px-4 py-3 text-left">Duracion</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y">
                        {services.length === 0 ? (
                            <tr>
                                <td
                                    className="px-4 py-6 text-center text-gray-500"
                                    colSpan="4"
                                >
                                    No hay servicios disponibles.
                                </td>
                            </tr>
                        ) : (
                            services.map((service) => (
                                <tr key={service.id}>
                                    <td className="px-4 py-3">
                                        {service.nombre}
                                    </td>
                                    <td className="px-4 py-3">
                                        {service.precio}
                                    </td>
                                    <td className="px-4 py-3">
                                        {service.descripcion}
                                    </td>
                                    <td className="px-4 py-3">
                                        {service.duracion}
                                    </td>

                                    <td className="px-4 py-3">
                                        <div className="flex items-center gap-3">
                                            {/**Editar */}
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    openUpdateServiceModal(
                                                        service
                                                    )
                                                }
                                            >
                                                <PencilSquareIcon className="h-5 w-5" />
                                            </button>

                                            {/**Eliminar */}
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    openDeleteServiceModal(
                                                        service.id
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

export default ServiciosAdmin;
