export default function Sidebar({ active, onChange }) {
    return (
        <aside className="w-64 bg-white border-r p-4">
            <ul className="space-y-2">
                <li>
                    <button
                        type="button"
                        onClick={() => onChange("home")}
                        className={`w-full text-left px-3 py-2 rounded-lg ${
                            active === "home"
                                ? "bg-gray-900 text-white"
                                : "hover:bg-gray-100"
                        }`}
                    >
                        Inicio
                    </button>
                </li>

                <li>
                    <button
                        type="button"
                        onClick={() => onChange("users")}
                        className={`w-full text-left px-3 py-2 rounded-lg ${
                            active === "users"
                                ? "bg-gray-900 text-white"
                                : "hover:bg-gray-100"
                        }`}
                    >
                        Usuarios
                    </button>
                </li>

                <li>
                    <button
                        type="button"
                        onClick={() => onChange("citas")}
                        className={`w-full text-left px-3 py-2 rounded-lg ${
                            active === "citas"
                                ? "bg-gray-900 text-white"
                                : "hover:bg-gray-100"
                        }`}
                    >
                        Citas
                    </button>
                </li>
            </ul>
        </aside>
    );
}
