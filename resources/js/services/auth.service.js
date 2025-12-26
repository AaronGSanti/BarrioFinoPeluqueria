import api from "@/lib/api";

export async function loginRequest(data) {
    await api.get("/sanctum/csrf-cookie");
    return api.post("/login", data);
}

export async function logoutRequest() {
    return api.post("/logout");
}

export async function registerRequest(data) {
    return api.post("/api/v1/usuarios/store", data);
}
