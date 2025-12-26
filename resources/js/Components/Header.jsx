import { Link, usePage } from "@inertiajs/react";
import { openLoginModal } from "@/modals/login.modal";
import { openRegisterModal } from "@/modals/register";

export default function Header({ canLogin, canRegister }) {
    const { auth } = usePage().props;
    const user = auth?.user;
    return (
        <header className="mx-auto max-w-6xl px-6 py-6 flex items-center justify-between">
            <div className="font-black text-xl">
                <img
                    className="w-64 h-auto-w-3xs"
                    src="/storage/logo(3).png"
                    alt="Barrio"
                />
            </div>

            <nav className="flex gap-3 items-center">
                {!user ? (
                    <>
                        {canLogin && (
                            <button
                                type="button"
                                onClick={openLoginModal}
                                className="px-4 py-2 rounded-lg border border-white/15 hover:border-white/30"
                            >
                                Login
                            </button>
                        )}

                        {canRegister && (
                            <button
                                type="button"
                                onClick={openRegisterModal}
                                className="px-4 py-2 rounded-lg bg-white text-black font-semibold"
                            >
                                Registro
                            </button>
                        )}
                    </>
                ) : (
                    <>
                        <span className="text-white/80 text-sm">
                            Hola, <b>{user.name}</b>
                        </span>

                        <Link
                            href={route("logout")}
                            method="post"
                            as="button"
                            className="px-4 py-2 rounded-lg border border-white/15 hover:border-white/30 text-sm"
                        >
                            Cerrar sesión
                        </Link>
                    </>
                )}
            </nav>
        </header>
    );
}
