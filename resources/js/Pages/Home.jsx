import { Head } from "@inertiajs/react";
import Header from "@/Components/Header";
import Section1 from "@/Components/Section1";
import Section2 from "@/Components/Section2";
import Section3 from "@/Components/Section3";

export default function Home({ canLogin, canRegister }) {
    return (
        <>
            <Head title="Barrio Fino" />

            {/* CONTENEDOR PRINCIPAL */}
            <div className="relative min-h-screen text-white overflow-hidden">

                {/* FONDO */}
                <div
                    className="absolute inset-0 bg-cover bg-center bg-no-repeat"
                    style={{
                        backgroundImage: "url('/storage/fondo.png')",
                    }}
                />

                {/* OVERLAY PARA BAJAR OPACIDAD */}
                <div className="absolute inset-0 bg-black/60" />

                {/* CONTENIDO */}
                <div className="relative z-10">
                    <Header canLogin={canLogin} canRegister={canRegister} />
                    <Section1 />
                    <Section2 />
                    <Section3/>
                </div>

            </div>
        </>
    );
}
