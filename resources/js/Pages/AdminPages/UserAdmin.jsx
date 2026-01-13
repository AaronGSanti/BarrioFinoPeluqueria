import {
    ArrowDownTrayIcon,
    ArrowUpTrayIcon,
    PencilSquareIcon,
    PlusIcon,
    TrashIcon,
} from "@heroicons/react/24/solid";
import { router } from "@inertiajs/react";
import openCreateUserModal from "./ModalsUser/CreateUserModal";

const UserAdmin = ({ users = [] }) => {
    return (
        <>
            <div>
                <h1 className="font-bold text-2xl">Panel de Usuario</h1>
            </div>

            <div className="mt-1">
                <p>Gestiona los usuarios de la aplicacion desde este panel.</p>
            </div>

            <div className="flex flex-wrap gap-4 justify-end">
                <button className="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    onClick={openCreateUserModal}>
                    <PlusIcon className="h-5 w-7 inline-block mr-1" />
                    Agregar Usuario
                </button>

                <button className="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <ArrowUpTrayIcon className="h-5 w-7 inline-block mr-1" />
                    Importar
                </button>

                <button className="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <ArrowDownTrayIcon className="h-5 w-7 inline-block mr-1" />
                    Exportar
                </button>
            </div>

            <div className="mt-6 overflow-hidden rounded-lg bg-white shadow">
                <table className="min-w-full text-sm">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="px-4 py-3 text-left">Nombre</th>
                            <th className="px-4 py-3 text-left">Email</th>
                            <th className="px-4 py-3 text-left">Rol</th>
                            <th className="px-4 py-3 text-left">Teléfono</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y">
                        {users.length === 0 ? (
                            <tr>
                                <td
                                    className="px-4 py-6 text-center text-gray-500"
                                    colSpan="4"
                                >
                                    No hay usuarios disponibles.
                                </td>
                            </tr>
                        ) : (
                            users.map((user) => (
                                <tr key={user.id}>
                                    <td className="px-4 py-3">{user.name}</td>
                                    <td className="px-4 py-3">{user.email}</td>
                                    <td className="px-4 py-3">{user.role}</td>
                                    <td className="px-4 py-3">
                                        {user.phone_number}
                                    </td>

                                    <td className="px-4 py-3">
                                        <div className="flex items-center gap-3">
                                            {/**Editar */}
                                            <button type="button">
                                                <PencilSquareIcon className="h-5 w-5"/>
                                            </button>

                                            {/**Eliminar */}
                                            <button type="button"
                                                onClick={() => {
                                                    if(confirm("¿Seguro que quieres eliminar este usuario?")){
                                                        router.delete(`/admin/users/${user.id}`)
                                                    }
                                                }}>
                                                <TrashIcon className="h-5 w-5"/>
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

export default UserAdmin;
