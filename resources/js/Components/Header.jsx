import { Link } from "@inertiajs/react";

export default function Header({ canLogin, canRegister }) {
    return (
        <header className="mx-auto max-w-6xl px-6 py-6 flex items-center justify-between">
            <div className="font-black text-xl">
                <img
                    className="w-64 h-auto-w-3xs"
                    src="/storage/logo(3).png"
                    alt="Barrio"
                />
            </div>

            <nav className="flex gap-3">
                {canLogin && (
                    <Link
                        href={route("login")}
                        className="px-4 py-2 rounded-lg border border-white/15 hover:border-white/30"
                    >
                        Login
                    </Link>
                )}
                {canRegister && (
                    <Link
                        href={route("register")}
                        className="px-4 py-2 rounded-lg bg-white text-black font-semibold"
                    >
                        Registro
                    </Link>
                )}
            </nav>
        </header>
    );
}
