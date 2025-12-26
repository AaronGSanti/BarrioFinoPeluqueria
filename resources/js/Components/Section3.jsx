export default function Section3() {
    return (
        <section className="mx-auto max-w-6xl grid grid-cols-3 md:grid-cols-3 gap-3 mt-5 items-center justify-center">
            <div>
                <h3 className="text-6xl font-black text-white text-center mb-5">
                    Informacion
                </h3>
                <h2 className="text-1xl mt-2 text-white/70  text-center">
                    Horario: Lunes - Sabado / 10:00 am - 08:00 pm
                </h2>
                <h2 className="text-1xl mt-2 text-white/70  text-center">
                    Correo electronico: barriofino@gmail.com
                </h2>
                <h2 className="text-1xl mt-2 text-white/70  text-center">
                    Direccion: Calle Mayor 3 , 28921 - Alcorcon
                </h2>
            </div>

            <div>
                <img
                    className="w-[300px] h-auto"
                    src="/storage/img2.png"
                    alt="Barrio"
                />
            </div>

            {/* MAPA */}
            <div className="w-full">
                <div className="relative w-full overflow-hidden border border-white/10 bg-zinc-900/30">
                    {/* Responsive mapa */}
                    <div className="aspect-video">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5418.286539894772!2d-3.8301470471964474!3d40.34432896998552!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd418eb466c5a991%3A0x8cf548bb8d5be419!2sKhalid%20Barber!5e0!3m2!1ses!2ses!4v1766407118087!5m2!1ses!2ses"
                            className="w-full h-full"
                            loading="lazy"
                            referrerPolicy="no-referrer-when-downgrade"
                            allowFullScreen
                        ></iframe>
                    </div>

                    {/* Como llegar */}
                    <div className="p-3 text-center">
                        <a
                            className="inline-block text-sm text-white/80 hover:text-white underline"
                            href="https://www.google.com/maps/place/Khalid+Barber/@40.344329,-3.830147,16.17z/data=!4m15!1m8!3m7!1s0xd418eb466c5a991:0x8cf548bb8d5be419!2sKhalid+Barber!8m2!3d40.3437084!4d-3.827714!10e1!16s%2Fg%2F11j_14v58_!3m5!1s0xd418eb466c5a991:0x8cf548bb8d5be419!8m2!3d40.3437084!4d-3.827714!16s%2Fg%2F11j_14v58_?entry=ttu&g_ep=EgoyMDI1MTIwOS4wIKXMDSoASAFQAw%3D%3D"
                            target="_blank"
                            rel="noreferrer"
                        >
                            Cómo llegar
                        </a>
                    </div>
                </div>
            </div>
        </section>
    );
}
