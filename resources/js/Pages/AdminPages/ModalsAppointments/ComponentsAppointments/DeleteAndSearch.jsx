import { MagnifyingGlassIcon, XMarkIcon } from "@heroicons/react/24/solid";
import { router } from "@inertiajs/react";
import { useState } from "react";

export default function AppointmentSearchAndDelete() {
    const [buscador, setBuscador] = useState("");
    const [desde, setDesde] = useState("");
    const [hasta, setHasta] = useState("");

    const handleChange = (e) => {
        setBuscador(e.target.value);
    };

    const handleSearch = () => {
        router.get(
            route("admin.citas.show"),
            { buscador, tab: "citas" },
            {
                preserveState: true,
                replace: true,
                preserveScroll: true,
            }
        );
    };

    const handleDelete = () => {
        setBuscador("");
        setDesde("");
        setHasta("");
        router.get(
            route("admin.citas.show"),
            { tab: "citas" },
            {
                preserveState: true,
                replace: true,
                preserveScroll: true,
            }
        );
    };

    return (
        <div className="mt-4 flex-col gap-3">
            <div className="flex flex-wrap items-end gap-3 rounded-lg bg-white p-3 shadow">
                {/**Buscador */}
                <div className="flex flex-col gap-1">
                    <label className="text-xs font-medium text-gray-600">
                        Buscar
                    </label>
                    <input
                        type="text"
                        placeholder="Cliente, barbero, servicio, estado..."
                        value={buscador}
                        onChange={(e) => setBuscador(e.target.value)}
                        className="w-72 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                    />
                </div>

                {/**Desde */}
                <div className="flex flex-col gap-1">
                    <label className="text-xs font-medium text-gray-600">
                        Desde
                    </label>
                    <input
                        type="date"
                        value={desde}
                        onChange={(e) => setDesde(e.target.value)}
                        className="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                    />
                </div>

                {/**Hasta */}
                <div className="flex flex-col gap-1">
                    <label className="text-xs font-medium text-gray-600">
                        Hasta
                    </label>
                    <input
                        type="date"
                        value={hasta}
                        onChange={(e) => setHasta(e.target.value)}
                        className="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
                    />
                </div>

                {/* Botones */}
                <div className="ml-auto flex items-center gap-2">
                    <button
                        type="button"
                        className="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                        onClick={handleSearch}
                    >
                        <MagnifyingGlassIcon className="h-5 w-5" />
                        Buscar
                    </button>

                    <button
                        type="button"
                        className="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                        onClick={handleDelete}
                    >
                        <XMarkIcon className="h-5 w-5" />
                        Limpiar
                    </button>
                </div>
            </div>
        </div>
    );
}
