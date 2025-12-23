import { openCalendario } from "@/modals/calendar";

export default function Section2() {
    return (
        <section className="mx-auto max-w-6xl  grid grid-cols-1 md:grid-cols-3 gap-6">
            {/* CARD 1 */}
            <div className="rounded-2xl border border-white/10 overflow-hidden flex flex-col gap-4 hover:border-white/30 transition">
                {/* IMAGEN */}
                <img
                    src="/storage/corte(2).png"
                    alt="Corte"
                    className="w-full h-48 object-contain mt-5"
                />

                {/* CARD 1 */}
                <div className="p-6 flex flex-col gap-4 flex-1">
                    <div>
                        <h3 className="text-5xl font-black text-white text-center">
                            Corte + Barba
                        </h3>
                        <p className="mt-2 text-white/70 text-sm text-center">
                            Corte completo, perfilado y estilo personalizado.
                        </p>
                        <p className="text-7xl text-end mt-5">15€</p>
                    </div>

                    <button
                        onClick={() =>
                            openCalendario({ servicio_id: 1, precio_total: 15 })
                        }
                        className="mt-auto px-4 py-2 rounded-lg border border-white/15 hover:border-white/30 text-sm"
                    >
                        Reservar
                    </button>
                </div>
            </div>

            {/* CARD 2 */}
            <div className="rounded-2xl border border-white/10 overflow-hidden flex flex-col gap-4 hover:border-white/30 transition">
                {/* IMAGEN */}
                <img
                    src="/storage/corte3(2).png"
                    alt="Corte"
                    className="w-full h-48 object-contain mt-5"
                />

                {/* CONTENIDO */}
                <div className="p-6 flex flex-col gap-4 flex-1">
                    <div>
                        <h3 className="text-5xl font-black text-white text-center">
                            Corte simple
                        </h3>
                        <p className="mt-2 text-white/70 text-sm text-center">
                            Corte limpio, preciso y a tu estilo.
                        </p>
                        <p className="text-7xl text-end mt-5">10€</p>
                    </div>

                    <button
                        onClick={() =>
                            openCalendario({ servicio_id: 2, precio_total: 10 })
                        }
                        className="mt-auto px-4 py-2 rounded-lg border border-white/15 hover:border-white/30 text-sm"
                    >
                        Reservar
                    </button>
                </div>
            </div>

            {/* CARD 3 */}
            <div className="rounded-2xl border border-white/10 overflow-hidden flex flex-col gap-4 hover:border-white/30 transition">
                {/* IMAGEN */}
                <img
                    src="/storage/corte2(2).png"
                    alt="Corte"
                    className="w-full h-48 object-contain mt-5"
                />

                {/* CONTENIDO */}
                <div className="p-6 flex flex-col gap-4 flex-1">
                    <div>
                        <h3 className="text-5xl font-black text-white text-center">
                            Barba + cejas
                        </h3>
                        <p className="mt-2 text-white/70 text-sm text-center">
                            Corte completo, barba perfilada y look impecable.
                        </p>
                        <p className="text-7xl text-end mt-5">5€</p>
                    </div>

                    <button
                        onClick={() =>
                            openCalendario({ servicio_id: 3, precio_total: 5 })
                        }
                        className="mt-auto px-4 py-2 rounded-lg border border-white/15 hover:border-white/30 text-sm"
                    >
                        Reservar
                    </button>
                </div>
            </div>
        </section>
    );
}
