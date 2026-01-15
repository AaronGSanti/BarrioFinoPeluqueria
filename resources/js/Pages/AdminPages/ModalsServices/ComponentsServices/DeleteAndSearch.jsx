import { MagnifyingGlassIcon, XMarkIcon } from "@heroicons/react/24/solid";
import { router } from "@inertiajs/react";
import { useState } from "react";

export default function ServiceSearchAndDelete() {
    const [buscador, setBuscador] = useState("");

    const handleChange = (e) => {
        setBuscador(e.target.value);
    };

    const handleSearch = () => {
        router.get(
            route("admin.service.show"),
            { buscador, tab: "servicios" },
            {
                preserveState: true,
                replace: true,
                preserveScroll: true,
            }
        );
    };

    const handleDelete = () => {
        setBuscador("");
        router.get(
            route("admin.service.show"),
            { tab: "servicios" },
            {
                preserveState: true,
                replace: true,
                preserveScroll: true,
            }
        );
    };

    return (
        <div className="flex flex-wrap items-end gap-3 rounded-lg bg-white p-3 shadow">
            <input
                type="text"
                placeholder="Buscar servicio"
                value={buscador}
                onChange={handleChange}
                className="w-72 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none"
            />
            {/**Buscar */}
            <button
                type="button"
                className="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                onClick={handleSearch}
            >
                <MagnifyingGlassIcon className="h-5 w-5" />
                Buscar
            </button>

            {/**Limpiar */}
            <button
                type="button"
                className="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                onClick={handleDelete}
            >
                <XMarkIcon className="h-5 w-5" />
                Limpiar
            </button>
        </div>
    );
}
