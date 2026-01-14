import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, router, usePage } from "@inertiajs/react";
import { useEffect, useState } from "react";

import Sidebar from "@/Components/Sidebar";
import HomeAdmin from "./AdminPages/HomeAdmin";
import UserAdmin from "./AdminPages/UserAdmin";
import CitasAdmin from "./AdminPages/CitasAdmin";
import ServiciosAdmin from "./AdminPages/ServiciosAdmin";

export default function Dashboard({ users = [], services = [] }) {
    const { url } = usePage();

    const getTabFromUrl = () => {
        const qs = url.includes("?") ? url.split("?")[1] : "";
        const params = new URLSearchParams(qs);
        return params.get("tab") || "home";
    };

    const [active, setActive] = useState(getTabFromUrl());

    // Si la URL cambia (por redirects/actions), mantenemos el tab sync
    useEffect(() => {
        setActive(getTabFromUrl());
    }, [url]);

    const handleChangeTab = (tab) => {
        setActive(tab);
        router.get(
            route("dashboard"),
            { tab },
            { preserveState: true, replace: true, preserveScroll: true }
        );
    };

    return (
        <AuthenticatedLayout>
            <Head title="Dashboard" />
            <div className="flex min-h-screen">
                <Sidebar active={active} onChange={handleChangeTab} />
                <div className="flex-1 p-6">
                    {active === "home" && <HomeAdmin />}
                    {active === "users" && <UserAdmin users={users} />}
                    {active === "servicios" && (
                        <ServiciosAdmin services={services} />
                    )}
                    {active === "citas" && <CitasAdmin />}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
