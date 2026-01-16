import {
    UsersIcon,
    CalendarDaysIcon,
    ScissorsIcon,
} from "@heroicons/react/24/outline";
import { router } from "@inertiajs/react";

const HomeAdmin = ( {contadores = {}}) => {
    return (
        <div className="space-y-8">
            {/* Header */}
            <div>
                <h1 className="text-2xl font-bold text-gray-800">
                    Bienvenido al Panel de Administración
                </h1>
                <p className="mt-1 text-sm text-gray-500">
                    Aqui gestionaremos usuarios, citas y servicios desde un solo
                    lugar.
                </p>
            </div>

            {/* Cards */}
            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                {/* Usuarios */}
                <div
                    role="button"
                    onClick={() =>
                        router.get(
                            route("dashboard"),
                            { tab: "users" },
                            {
                                preserveState: true,
                                replace: true,
                                preserveScroll: true,
                            }
                        )
                    }
                    className="cursor-pointer rounded-xl bg-white p-6 shadow transition hover:shadow-md hover:ring-2 hover:ring-blue-500"
                >
                    <div className="flex items-center gap-4">
                        <div className="rounded-lg bg-blue-100 p-3">
                            <UsersIcon className="h-6 w-6 text-blue-600" />
                        </div>
                        <div>
                            <p className="text-sm text-gray-500">Usuarios</p>
                            <p className="text-xl font-semibold text-gray-800">
                                {contadores.users ?? 0}
                            </p>
                        </div>
                    </div>
                    <p className="mt-4 text-sm text-gray-500">
                        Gestiona clientes y barberos registrados.
                    </p>
                </div>

                {/* Servicios */}
                <div
                    role="button"
                    onClick={() =>
                        router.get(
                            route("dashboard"),
                            { tab: "servicios" },
                            {
                                preserveState: true,
                                replace: true,
                                preserveScroll: true,
                            }
                        )
                    }
                    className="cursor-pointer rounded-xl bg-white p-6 shadow transition hover:shadow-md hover:ring-2 hover:ring-purple-500"
                >
                    <div className="flex items-center gap-4">
                        <div className="rounded-lg bg-purple-100 p-3">
                            <ScissorsIcon className="h-6 w-6 text-purple-600" />
                        </div>
                        <div>
                            <p className="text-sm text-gray-500">Servicios</p>
                            <p className="text-xl font-semibold text-gray-800">
                                {contadores.services ?? 0}
                            </p>
                        </div>
                    </div>
                    <p className="mt-4 text-sm text-gray-500">
                        Administra los servicios disponibles y sus precios.
                    </p>
                </div>

                {/* Citas */}
                <div
                    role="button"
                    onClick={() =>
                        router.get(
                            route("dashboard"),
                            { tab: "citas" },
                            {
                                preserveState: true,
                                replace: true,
                                preserveScroll: true,
                            }
                        )
                    }
                    className="cursor-pointer rounded-xl bg-white p-6 shadow transition hover:shadow-md hover:ring-2 hover:ring-green-500"
                >
                    <div className="flex items-center gap-4">
                        <div className="rounded-lg bg-green-100 p-3">
                            <CalendarDaysIcon className="h-6 w-6 text-green-600" />
                        </div>
                        <div>
                            <p className="text-sm text-gray-500">Citas</p>
                            <p className="text-xl font-semibold text-gray-800">
                                {contadores.citas ?? 0}
                            </p>
                        </div>
                    </div>
                    <p className="mt-4 text-sm text-gray-500">
                        Visualiza y administra las citas programadas.
                    </p>
                </div>
            </div>
        </div>
    );
};

export default HomeAdmin;
