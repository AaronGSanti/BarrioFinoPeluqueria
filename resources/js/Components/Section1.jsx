export default function Section1() {
    return (
            <section className="mx-auto flex flex-nowrap items-center justify-center">
                <div>
                    <h1 className="text-6xl font-black">
                        Tu estilo,{" "}
                        <span className="text-white/60">tu barrio</span>
                    </h1>

                    <p className="mt-6 text-white/70 max-w-xl text-lg">
                        Bienvenido a Barrio Fino. Donde el corte es respeto, el
                        estilo se gana y cada visita se siente como en casa.
                        Aquí no seguimos modas, las marcamos.
                    </p>

                    <nav className="mt-8 flex gap-4">
                        <a
                            href="#servicios"
                            className="px-5 py-3 rounded-lg border border-white/15 hover:border-white/30"
                        >
                            Servicios
                        </a>
                        <a
                            href="#contacto"
                            className="px-5 py-3 rounded-lg border border-white/15 hover:border-white/30"
                        >
                            Contacto
                        </a>
                    </nav>
                </div>

                <div>
                    <img
                        className="w-[350px] h-auto"
                        src="/storage/img1.png"
                        alt="Barrio"
                    />
                </div>
            </section>
    );
}
