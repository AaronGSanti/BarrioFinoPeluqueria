import { MagnifyingGlassIcon, XMarkIcon } from "@heroicons/react/24/solid";
import { router } from "@inertiajs/react";
import { useState } from "react";

export default function SearchAndDelete() {
    const [buscador, setBuscador] = useState("");

    const handleChange = (e) => {
        setBuscador(e.target.value);
    };

    const handleSearch = () => {
        router.get(
            route("admin.users.show"),
            { buscador , tab: 'users'},
            {
                /**Mantiene el estado useState , el texto del buscador se queda. */
                preserveState: true,
                /**Reemplaza la url en el historial del navegador. */
                replace: true,
                /**Mantiene el scroll de la pagina donde estaba (sigue en la misma posicion). */
                preserveScroll: true,
            }
        );
    };

    const handleDelete = () => {
        setBuscador("");
        router.get(
            route("admin.users.show"),
            {tab: "users"},
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
                placeholder="Buscar usuario"
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
