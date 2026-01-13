import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head } from "@inertiajs/react";
import { useState } from "react";

import Sidebar from "@/Components/Sidebar";
import HomeAdmin from "./AdminPages/HomeAdmin";
import UserAdmin from "./AdminPages/UserAdmin";
import CitasAdmin from "./AdminPages/CitasAdmin";

export default function Dashboard({users = []}) {
    const [active, setActive] = useState("home");

    return (
        <AuthenticatedLayout>
            <Head title="Dashboard" />
            <div className="flex">
                <Sidebar active={active} onChange={setActive} />
                <div className="flex-1 p-6">
                    {active === "home" && <HomeAdmin />}
                    {active === "users" && <UserAdmin users={users} />}
                    {active === "citas" && <CitasAdmin />}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
