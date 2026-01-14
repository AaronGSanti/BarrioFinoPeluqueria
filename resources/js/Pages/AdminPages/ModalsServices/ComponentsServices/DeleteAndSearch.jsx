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
        <div className="mt-1 flex items-center gap-2">
            <input
                type="text"
                placeholder="Buscar servicio"
                value={buscador}
                onChange={handleChange}
                className="border rounded px-3 py-2"
            />
            {/**Buscar */}
            <button
                type="button"
                className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                onClick={handleSearch}
            >
                <MagnifyingGlassIcon className="h-5 w-5" />
            </button>

            {/**Limpiar */}
            <button
                type="button"
                className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                onClick={handleDelete}
            >
                <XMarkIcon className="w-5 h-5" />
            </button>
        </div>
    );
}
